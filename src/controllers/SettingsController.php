<?php
require_once __DIR__ . '/../models/SettingsModel.php';
class SettingsController
{
  private $settingsModel;

  public function __construct()
  {
    $this->settingsModel = new SettingsModel();
  }

  public function showForm()
  {
    // Lấy dữ liệu hiện tại từ database
    $currentSettings = $this->settingsModel->getLatest();
    require_once __DIR__ . '/../views/settings/Form.php';
  }

  public function saveSettings()
  {
    // Kiểm tra xem request có phải là POST không
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(["status" => "error", "message" => "Invalid request method"]);
      return;
    }

    // Lấy dữ liệu từ form
    $hotelName = $_POST['hotel_name'] ?? null;
    $phoneNumber = $_POST['phone_number'] ?? null;
    $address = $_POST['address'] ?? null;
    $logo = $_FILES['logo'] ?? null;

    // Kiểm tra các trường bắt buộc
    if (!$hotelName || !$phoneNumber || !$address || !$logo) {
      echo json_encode([
        "status" => "error", 
        "message" => "Missing required fields",
        "details" => [
          "hotel_name" => !$hotelName,
          "phone_number" => !$phoneNumber,
          "address" => !$address,
          "logo" => !$logo
        ]
      ]);
      return;
    }

    try {
      // Xử lý file logo
      $uploadDir = __DIR__ . '/../uploads/';
      if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $logoFileName = time() . '_' . basename($logo['name']);
      $targetFile = $uploadDir . $logoFileName;

      if (!move_uploaded_file($logo['tmp_name'], $targetFile)) {
        throw new Exception("Failed to upload logo");
      }

      // Chuẩn bị dữ liệu để lưu
      $settingsData = [
        'hotel_name' => $hotelName,
        'phone_number' => $phoneNumber,
        'address' => $address,
        'logo_path' => $logoFileName
      ];

      // Lưu vào database thông qua model
      $result = $this->settingsModel->save($settingsData);

      if ($result) {
        header("Location: /../views/settings/success.php");
        echo "Settings saved successfully";
      } else {
        throw new Exception("Failed to save settings to database");
      }

    } catch (Exception $e) {
      echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
      ]);
    }
  }

  public function showSuccess()
  {
    require_once __DIR__ . '/../views/settings/Success.php';
  }
}