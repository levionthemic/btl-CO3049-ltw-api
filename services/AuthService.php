<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../providers/JwtProvider.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthService
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function login($data)
  {
    try {
      $user = $this->userModel->findOneByEmail($data['email']);

      if (!$user) {
        throw new ApiError("Account not existed!", 403);
      }

      if (isset($data['role']) && $user['role'] != 'admin') {
        throw new ApiError("Require Admin!", 403);
      }

      if ($user['status'] == 'inactive') {
        throw new ApiError("Your account had been locked!", 403);
      }

      if (!password_verify($data['password'], $user['password'])) {
        throw new ApiError("Incorrect password!", 403);
      }

      $userInfo = [
        "id" => $user['id'],
        "email" => $user['email']
      ];

      $accessToken = JwtProvider::generateToken(
        $userInfo,
        $_ENV['ACCESS_TOKEN_SECRET_SIGNATURE'],
        $_ENV['ACCESS_TOKEN_LIFE']
      );

      $refreshToken = JwtProvider::generateToken(
        $userInfo,
        $_ENV['REFRESH_TOKEN_SECRET_SIGNATURE'],
        $_ENV['REFRESH_TOKEN_LIFE']
      );

      return [
        "user" => $user,
        "accessToken" => $accessToken,
        "refreshToken" => $refreshToken
      ];
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function register($data)
  {
    try {
      $user = $this->userModel->findOneByEmail($data['email']);

      if ($user) throw new ApiError('Email existed!', 403);

      $userData = [
        "name" => $data['name'],
        "email" => $data['email'],
        "password" => password_hash($data['password'], PASSWORD_BCRYPT)
      ];

      $response = $this->userModel->create($userData);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function refreshToken($clientRefreshToken)
  {
    try {
      $refreshTokenPayload = JwtProvider::verify($clientRefreshToken, $_ENV['REFRESH_TOKEN_SECRET_SIGNATURE']);

      $userInfo = [
        "id" => $refreshTokenPayload->id,
        "email" => $refreshTokenPayload->email
      ];

      $accessToken = JwtProvider::generateToken(
        $userInfo,
        $_ENV['ACCESS_TOKEN_SECRET_SIGNATURE'],
        $_ENV['ACCESS_TOKEN_LIFE']
      );

      return ['accessToken' => $accessToken];
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function forgotPassword($data)
  {
    try {
      $email = $data['email'];

      $user = $this->userModel->findOneByEmail($email);
      if (!$user) {
        throw new ApiError("Account not existed!", 403);
      }

      $otp = rand(100000, 99999999);

      session_start();
      $_SESSION['otp'] = $otp;
      $_SESSION['otp_expiry'] = time() + 180; // hết hạn sau 3 phút

      $mail = new PHPMailer(true);
      // Cấu hình SMTP
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com'; // ví dụ dùng Gmail
      $mail->SMTPAuth   = true;
      $mail->Username   = $_ENV['EMAIL_USER'];
      $mail->Password   = $_ENV['EMAIL_PASSWORD']; // dùng App Password nếu Gmail
      $mail->SMTPSecure = 'tls';
      $mail->Port       = 587;

      // Nội dung email
      $mail->setFrom($_ENV['EMAIL_USER'], 'Hotel Booking App');
      $mail->addAddress($email, $user['name']);
      $mail->Subject = 'Your OTP Code';
      $mail->Body    = "Your OTP is: $otp. It will expire in 3 minutes.";

      $mail->send();

      return;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function verifyCode($data)
  {
    try {
      session_start();

      $otp = $data['otp'];

      if ($_SESSION['otp'] == $otp && time() < $_SESSION['otp_expiry']) {
        $userInfo = ['email' => $data['email']];
        $resetToken = JwtProvider::generateToken(
          $userInfo,
          $_ENV['ACCESS_TOKEN_SECRET_SIGNATURE'],
          $_ENV['ACCESS_TOKEN_LIFE']
        );
        return ["status" => "success", "resetToken" => $resetToken];
      } else {
        throw new ApiError("OTP is invalid or expired", 406) ;
      }
    } catch (Exception $e) {
      throw $e;
    }
  }
}
