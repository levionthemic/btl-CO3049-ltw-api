<?php
require_once __DIR__ . '/../models/SettingsModel.php';
class SettingsController
{
  private $settingsModel;

  public function __construct()
  {
    $this->settingsModel = new SettingsModel();
    $this->setupCORS();
  }

  private function setupCORS()
  {
    // Cho phép từ domain cụ thể
    header('Access-Control-Allow-Origin: http://localhost:5173');
    
    // Cho phép các phương thức HTTP
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    
    // Cho phép các headers
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    
    // Cho phép gửi credentials (cookies, authorization headers)
    header('Access-Control-Allow-Credentials: true');
    
    // Xử lý preflight request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      header('HTTP/1.1 200 OK');
      exit();
    }
  }

  public function showForm()
  {
    // Lấy dữ liệu hiện tại từ database
    $currentSettings = $this->settingsModel->getLatest();
    require_once __DIR__ . '/../views/settings/Form.php';
  }

  public function saveSettings()
  {
    // Set header JSON cho API response
    header('Content-Type: application/json');

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
        echo json_encode([
          "status" => "success",
          "message" => "Settings saved successfully",
          "data" => $settingsData
        ]);
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

  public function getRandom()
  {
    // Set header để trả về JSON
    header('Content-Type: application/json');
    
    // Tạo số ngẫu nhiên
    $randomNumber = rand(1, 100);
    
    // Trả về JSON response
    echo json_encode([
        'name' => 'random',
        'value' => $randomNumber
    ]);
  }
}