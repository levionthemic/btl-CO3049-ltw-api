<?php
require_once __DIR__ . '/../config/database.php';

class SettingsModel {
    private $conn;

    public function __construct() {
        global $conn; // Use the connection from database.php
        $this->conn = $conn;
    }

    public function get() {
        $stmt = $this->conn->query("SELECT * FROM Setting LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatest() {
        $stmt = $this->conn->query("SELECT * FROM Setting ORDER BY updated_at DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO Setting (hotel_name, phone_number, address) 
                VALUES (:hotel_name, :phone_number, :address)
            ");
    
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            error_log("Database Save Error: " . $e->getMessage()); // Log error
            return false;
        }
    }
}
?>
