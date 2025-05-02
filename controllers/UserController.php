<?php

require_once __DIR__ . '/../services/UserService.php';

class UserController
{
  private $userService;

  public function __construct()
  {
    $this->userService = new UserService();
  }

  public function getAllUsers()
  {
    try {
      $users = $this->userService->getAll();
      echo json_encode(["status" => "success", "data" => $users]);
    } catch (Exception $e) {
      throw $e;
    }
  }

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

  public function editUser()
  {
    try {
      // Handle upload avatar
      if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
          throw new Exception("Invalid file type. Only JPG, PNG, GIF, WEBP allowed.");
        }

        if ($file['size'] > $maxSize) {
          throw new Exception("File size exceeds 2MB limit.");
        }

        $uploadDir = __DIR__ . "/../uploads/";
        if (!file_exists($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid("img_", true) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
          throw new Exception("Failed to move uploaded file.");
        }

        $updateData = [
          "avatar" => "/uploads/" . $fileName, 
          "id" => $_POST['id'],
          "name" => $_POST['name'],
          "email" => $_POST['email'],
          "address" => $_POST['address'],
          "phone" => $_POST['phone'],
          "status" => $_POST['status'],
          "newPassword" => $_POST['newPassword'],
        ];
      } else {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
          throw new ApiError('Định dạng email không hợp lệ.', 406);
        }
        if (!preg_match('/^[0-9]{9,15}$/', $input['phone'])) {
          throw new ApiError('Số điện thoại không hợp lệ (chỉ chấp nhận 9-15 chữ số).', 406);
        }
        $updateData = $input;
      }
      $result = $this->userService->editUser($updateData);
      echo json_encode(["status" => "success", "data" => $result]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function updateUser()
  {
    try {
      // Handle upload avatar
      if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
          throw new Exception("Invalid file type. Only JPG, PNG, GIF, WEBP allowed.");
        }

        if ($file['size'] > $maxSize) {
          throw new Exception("File size exceeds 2MB limit.");
        }

        $uploadDir = __DIR__ . "/../uploads/";
        if (!file_exists($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid("img_", true) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
          throw new Exception("Failed to move uploaded file.");
        }

        $updateData = ["avatar" => "/uploads/" . $fileName, "id" => $_POST['id']];
      } else {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
          throw new ApiError('Định dạng email không hợp lệ.', 406);
        }
        if (!preg_match('/^[0-9]{9,15}$/', $input['phone'])) {
          throw new ApiError('Số điện thoại không hợp lệ (chỉ chấp nhận 9-15 chữ số).', 406);
        }
        $updateData = $input;
      }
      $result = $this->userService->updateUser($updateData);
      echo json_encode(["status" => "success", "data" => $result]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function deleteUser($id)
  {
    try {
      $result = $this->userService->deleteUser($id);
      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
