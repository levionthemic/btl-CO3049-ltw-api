<?php

require_once __DIR__ . '/../models/User.php';

class AuthService
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function login($email, $password)
  {
    $user = $this->userModel->findOneByEmail($email);
    if (!$user || !password_verify($password, $user['password'])) {
      return ["status" => "error", "message" => "Incorrect username or password"];
    }

    return ["status" => "success", "userId" => $user['id'], "message" => "Login successful"];
  }
}
