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
    header('Access-Control-Allow-Origin: *');
    
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

    try {
      // Lấy dữ liệu từ form
      $hotelName = $_POST['hotel_name'] ?? null;
      $phoneNumber = $_POST['phone_number'] ?? null;
      $address = $_POST['address'] ?? null;
      $logo = $_FILES['logo'] ?? null;

      // Debug log
      error_log("Received data: " . print_r($_POST, true));
      error_log("Received files: " . print_r($_FILES, true));

      // Kiểm tra các trường bắt buộc
      if (!$hotelName || !$phoneNumber || !$address) {
        echo json_encode([
          "status" => "error", 
          "message" => "Missing required fields",
          "details" => [
            "hotel_name" => !$hotelName,
            "phone_number" => !$phoneNumber,
            "address" => !$address
          ]
        ]);
        return;
      }

      // Chuẩn bị dữ liệu để lưu
      $settingsData = [
        'hotel_name' => $hotelName,
        'phone_number' => $phoneNumber,
        'address' => $address
      ];

      // Xử lý file logo
      if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
        // Nếu có file mới được upload
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }

        $logoFileName = time() . '_' . basename($logo['name']);
        $targetFile = $uploadDir . $logoFileName;

        if (move_uploaded_file($logo['tmp_name'], $targetFile)) {
          $settingsData['logo_path'] = $logoFileName;
        }
      } else {
        // Nếu không có file mới, lấy logo hiện tại từ database
        $currentSettings = $this->settingsModel->getLatest();
        if (isset($currentSettings['logo_path'])) {
          $settingsData['logo_path'] = $currentSettings['logo_path'];
        }
      }

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
      error_log("Error in saveSettings: " . $e->getMessage());
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

  public function getLatest() {
    header('Content-Type: application/json');
    echo json_encode($this->settingsModel->getLatest());
  }
}