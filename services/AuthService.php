<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../providers/JwtProvider.php';

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
}
