<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function getAllUsers()
  {
    $users = $this->userModel->getAll();
    echo json_encode($users);
  }

  public function getUserById($id)
  {
    $user = $this->userModel->findById($id);
    if ($user) {
      echo json_encode($user);
    } else {
      http_response_code(404);
      echo json_encode(["error" => "User not found"]);
    }
  }
}
