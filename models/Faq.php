<?php

require_once __DIR__ . '/../config/database.php';

class Faq
{
  private $conn;

  public function __construct()
  {
    $this->conn = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->conn->query("SELECT * FROM faqs");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createOne($data)
  {
    $stmt = $this->conn->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");

    $result = $stmt->execute([$data['question'], $data['answer']]);

    if ($result) {
      return $this->conn->lastInsertId();
    } else {
      return false;
    }
  }


  public function updateFaq($data)
  {
    $stmt = $this->conn->prepare("UPDATE faqs SET question = ?, answer = ? WHERE id = ?");

    $result = $stmt->execute([$data['question'], $data['answer'], $data['id']]);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteFaq($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM faqs WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result) {
      return $stmt->rowCount();
    } else {
      return false;
    }
  }
}
