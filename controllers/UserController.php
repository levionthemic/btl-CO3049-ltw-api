<?php

require_once __DIR__ . '/../services/UserService.php';

class UserController
{
  private $userService;

  public function __construct()
  {
    $this->userService = new UserService();
  }

  // public function getAllUsers()
  // {
  //   $users = $this->userModel->getAll();
  //   echo json_encode($users);
  // }

  // public function getUserById($id)
  // {
  //   $user = $this->userModel->findById($id);
  //   if ($user) {
  //     echo json_encode($user);
  //   } else {
  //     http_response_code(404);
  //     echo json_encode(["error" => "User not found"]);
  //   }
  // }

  public function updateUser()
  {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $input = json_decode(file_get_contents("php://input"), true);

      // if (!isset($input['id']) || !isset($input['question']) || !isset($input['answer'])) {
      //   throw new ApiError('Missing information', 406);
      // }

      $result = $this->userService->updateUser($input);
      echo json_encode(["status" => "success", "data" => $result]);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
