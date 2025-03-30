<?php
require_once __DIR__ . '/../config/database.php';

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
}
?>
