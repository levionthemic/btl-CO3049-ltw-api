<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/AuthService.php';

class AuthController
{
  private $authService;

  public function __construct()
  {
    $this->authService = new AuthService();
  }

  public function login()
  {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['email']) || !isset($input['password']) || !isset($input['rememberMe'])) {
        throw new ApiError('Missing information', 406);
      }

      $response = $this->authService->login($input);

      if ($input['rememberMe']) {
        setcookie(
          'accessToken',
          $response['accessToken'],
          [
            'expires' => time() + 7 * 24 * 60 * 60,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None'
          ]
        );
        setcookie(
          'refreshToken',
          $response['refreshToken'],
          [
            'expires' => time() + 7 * 24 * 60 * 60,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None'
          ]
        );
      } else {
        setcookie(
          'accessToken',
          $response['accessToken'],
          [
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None'
          ]
        );
        setcookie(
          'refreshToken',
          $response['refreshToken'],
          [
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None'
          ]
        );
      }



      unset($response['accessToken']);
      unset($response['refreshToken']);

      echo json_encode(["status" => "success", "data" => $response]);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function register()
  {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['email']) || !isset($input['password']) || !isset($input['name'])) {
        throw new ApiError('Missing information', 406);
      }

      $response = $this->authService->register($input);

      echo json_encode(["status" => "success", "data" => $response]);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function logout()
  {
    try {
      setcookie(
        'accessToken',
        '',
        [
          'expires' => time() - 1,
          'path' => '/',
          'secure' => true,
          'httponly' => true,
          'samesite' => 'None'
        ]
      );
      setcookie(
        'refreshToken',
        '',
        [
          'expires' => time() - 1,
          'path' => '/',
          'secure' => true,
          'httponly' => true,
          'samesite' => 'None'
        ]
      );
      unset($_COOKIE['accessToken']);
      unset($_COOKIE['refreshToken']);

      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function refreshToken()
  {
    try {
      if (!isset($_COOKIE['refreshToken'])) {
        throw new ApiError("Unauthorized! Please Login", 401);
      }
      $response = $this->authService->refreshToken($_COOKIE['refreshToken']);

      setcookie(
        'accessToken',
        $response['accessToken'],
        [
          'expires' => time() + 7 * 24 * 60 * 60,
          'path' => '/',
          'secure' => true,
          'httponly' => true,
          'samesite' => 'None'
        ]
      );

      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function forgotPassword()
  {
    try {
      header('Content-Type: application/json; charset=utf-8');
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['email'])) {
        throw new ApiError('Missing information', 406);
      }

      $result = $this->authService->forgotPassword($input);
      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function verifyCode()
  {
    try {
      header('Content-Type: application/json; charset=utf-8');
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['email']) || !isset($input['otp'])) {
        throw new ApiError('Missing information', 406);
      }

      $result = $this->authService->verifyCode($input);
      echo json_encode($result);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function resetPassword()
  {
    try {
      header('Content-Type: application/json; charset=utf-8');
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['password']) || !isset($input['resetToken'])) {
        throw new ApiError('Missing information', 406);
      }

      $result = $this->authService->resetPassword($input);
      echo json_encode($result);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
