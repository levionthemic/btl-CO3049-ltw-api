<?php
require_once __DIR__ . '/../config/database.php';

// CREATE TABLE contacts (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,     
//     email VARCHAR(255) NOT NULL,       
//     message TEXT NOT NULL,  
//     status ENUM('unread', 'read', 'responded') DEFAULT 'unread',
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
// );

class ContactsModel
{
    private $conn;

    public function __construct()
    {
        global $conn; // Use the existing PDO connection from database.php
        $this->conn = $conn;
    }

    // Get all contacts
    public function getAllContacts()
    {
        $stmt = $this->conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single contact by ID
    public function getContactById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAsRead($id)
    {
        $stmt = $this->conn->prepare("UPDATE contacts SET status = 'read' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function markAsResponded($id)
    {
        $stmt = $this->conn->prepare("UPDATE contacts SET status = 'responded' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Add a new contact
    public function addContact($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO contacts (name, email, message) 
            VALUES (:name, :email, :message)
        ");
    
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':message' => $data['message']
        ]);
    }

    // Update contact status (e.g., 'read', 'responded')
    public function updateStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE contacts SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Delete a contact
    public function deleteContact($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPaginatedContacts($page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $stmt = $this->conn->prepare("SELECT * FROM contacts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalContactsCount()
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM contacts");
        return $stmt->fetchColumn();
    }
}
?>
