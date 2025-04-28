<?php

use Firebase\JWT\ExpiredException;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../providers/JwtProvider.php';
require_once __DIR__ . '/../config/environment.php';

class AuthMiddleware
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function authenticate()
  {
    loadEnv();

    if (!isset($_COOKIE['accessToken'])) {
      throw new ApiError('Access token missing', 401);
    }

    $clientAccessToken = $_COOKIE['accessToken'];

    try {
      $tokenUserInfo = JwtProvider::verify($clientAccessToken, $_ENV['ACCESS_TOKEN_SECRET_SIGNATURE']);
    } catch (ExpiredException $e) {
      throw new ApiError('Token has expired', 410);
    } catch (Exception $e) {
      throw new ApiError('Invalid token', 401);
    }

    $user = $this->userModel->findOneByEmail($tokenUserInfo->email);

    if (!$user) {
      throw new ApiError("Unauthorized!", 401);
    }

    return ["status" => "success", "user" => $user];
  }
}
