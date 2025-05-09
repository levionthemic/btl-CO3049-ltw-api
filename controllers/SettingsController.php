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
    $currentSettings = $this->settingsModel->getLatest();
    require_once __DIR__ . '/../views/settings/Form.php';
  }

  public function saveSettings()
  {
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(["status" => "error", "message" => "Invalid request method"]);
      return;
    }

    try {
      $hotelName = $_POST['hotel_name'] ?? null;
      $phoneNumber = $_POST['phone_number'] ?? null;
      $address = $_POST['address'] ?? null;
      $logo = $_FILES['logo'] ?? null;

      error_log("Received data: " . print_r($_POST, true));
      error_log("Received files: " . print_r($_FILES, true));

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

      $settingsData = [
        'hotel_name' => $hotelName,
        'phone_number' => $phoneNumber,
        'address' => $address
      ];

      if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
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
        $currentSettings = $this->settingsModel->getLatest();
        if (isset($currentSettings['logo_path'])) {
          $settingsData['logo_path'] = $currentSettings['logo_path'];
        }
      }

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
    header('Content-Type: application/json');
    $randomNumber = rand(1, 100);
    echo json_encode([
      'name' => 'random',
      'value' => $randomNumber
    ]);
  }

  public function getLatest()
  {
    header('Content-Type: application/json');
    echo json_encode($this->settingsModel->getLatest());
  }
}
