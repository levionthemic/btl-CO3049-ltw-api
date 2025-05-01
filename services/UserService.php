<?php

require_once __DIR__ . '/../models/User.php';

class UserService
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function updateUser($data)
  {
    try {
      $response = $this->userModel->updateUser($data);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }
}
