<?php

require_once __DIR__ . '/../config/database.php';

class User
{
  private $conn;

  public function __construct()
  {
    $this->conn = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->conn->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findOneByEmail($email)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

    $result = $stmt->execute([$data['name'], $data['email'], $data['password']]);

    if ($result) {
      return $this->conn->lastInsertId();
    } else {
      return false;
    }
  }

  public function updateUser($data)
  {
    $stmt = $this->conn->prepare("UPDATE users SET email = ?, name = ?, address = ?, phone = ? WHERE id = ?");

    $result = $stmt->execute([$data['email'], $data['name'], $data['address'], $data['phone'], $data['id']]);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }
}
