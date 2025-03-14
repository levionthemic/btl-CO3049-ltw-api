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
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['email']) || !isset($input['password'])) {
      echo json_encode(["status" => "error", "message" => "Missing parameters"]);
      return;
    }

    $response = $this->authService->login($input['email'], $input['password']);
    echo json_encode($response);
  }

  public function register()
  {
    echo json_encode(["message" => "Register function here"]);
  }
}
