<?php

require_once __DIR__ . '/../models/User.php';

class UserService
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function getAll()
  {
    try {
      $response = $this->userModel->getAll();
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
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

  // Admin
  public function editUser($data)
  {
    try {
      $response = $this->userModel->editUser($data);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  // Admin
  public function deleteUser($id)
  {
    try {
      $response = $this->userModel->deleteUser($id);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }
}
