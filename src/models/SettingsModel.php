<?php
require_once __DIR__ . '/../config/database.php';

class SettingsModel {
    private $conn;

    public function __construct() {
        global $conn; // Use the connection from database.php
        $this->conn = $conn;
    }

    public function get() {
        // $stmt = $this->conn->query("
        // CREATE TABLE IF NOT EXISTS Setting (
        //     id INT AUTO_INCREMENT PRIMARY KEY,
        //     hotel_name VARCHAR(255) NOT NULL,
        //     phone_number VARCHAR(20) NOT NULL,
        //     address TEXT NOT NULL,
        //     logo_path VARCHAR(255) DEFAULT NULL,
        //     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        // );
        // "); //In case the table is not created
        
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
                INSERT INTO Setting (hotel_name, phone_number, address, logo_path) 
                VALUES (:hotel_name, :phone_number, :address, :logo_path)
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
