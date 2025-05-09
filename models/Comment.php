<?php

require_once __DIR__ . '/../config/database.php';

class Comment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getByNews($news_id) {
        $stmt = $this->conn->prepare("SELECT * FROM news_comments WHERE news_id = ? ORDER BY created_at ASC");
        $stmt->execute([$news_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOne($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO news_comments (news_id, user_id, content, parent_id)
            VALUES (?, ?, ?, ?)
        ");

        $result = $stmt->execute([
            $data['news_id'],
            $data['user_id'],
            $data['content'],
            $data['parent_id'] ?? null
        ]);

        if ($result) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }
    public function deleteOne($id) {
        $stmt = $this->conn->prepare("DELETE FROM news_comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
