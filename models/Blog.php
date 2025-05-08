<?php

require_once __DIR__ . '/../config/database.php';

class Blog
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM news ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword)
    {
        $stmt = $this->conn->prepare("SELECT * FROM news WHERE title LIKE ?");
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOne($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
        $result = $stmt->execute([
            $data['title'],
            $data['content'],
            $data['image'] ?? ''
        ]);

        if ($result) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function updatePost($data)
    {
        $stmt = $this->conn->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
        $result = $stmt->execute([
            $data['title'],
            $data['content'],
            $data['image'],
            $data['id']
        ]);

        return $result;
    }

    public function deletePost($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM news WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result) {
            return $stmt->rowCount();
        } else {
            return false;
        }
    }
}
