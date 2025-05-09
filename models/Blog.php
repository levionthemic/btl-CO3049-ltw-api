<?php

require_once __DIR__ . '/../config/database.php';

class Blog
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
        if (!$this->conn) {
            error_log('Blog.php: Database connection is null');
            throw new Exception('Không thể kết nối database');
        }
    }

    public function getAll()
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM news ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Blog.php getAll error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM news WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Blog.php getById error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function search($keyword)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM news WHERE title LIKE ?");
            $stmt->execute(['%' . $keyword . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Blog.php search error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createOne($data, $imagePath = null)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $data['title'],
                $data['content'],
                $imagePath
            ]);

            if ($result) {
                return $this->conn->lastInsertId();
            } else {
                error_log('Blog.php createOne: Failed to insert post');
                return false;
            }
        } catch (Exception $e) {
            error_log('Blog.php createOne error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatePost($data, $imagePath = null)
    {
        try {
            $query = "UPDATE news SET title = ?, content = ?, updated_at = NOW()";
            $params = [$data['title'], $data['content']];

            if ($imagePath !== null) {
                $query .= ", image = ?";
                $params[] = $imagePath;
            }

            $query .= " WHERE id = ?";
            $params[] = $data['id'];

            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($params);

            if (!$result) {
                error_log('Blog.php updatePost: Failed to execute update query');
                throw new Exception('Failed to update post');
            }

            return $result;
        } catch (Exception $e) {
            error_log('Blog.php updatePost error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deletePost($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM news WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                return $stmt->rowCount();
            } else {
                error_log('Blog.php deletePost: Failed to delete post');
                return false;
            }
        } catch (Exception $e) {
            error_log('Blog.php deletePost error: ' . $e->getMessage());
            throw $e;
        }
    }
}