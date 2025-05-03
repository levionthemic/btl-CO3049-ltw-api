<?php
require_once __DIR__ . '/../config/database.php';

class Room
{
  private $conn;

  public function __construct()
  {
    $this->conn = Database::getInstance()->getConnection();
  }

  public function getById($id)
  {
    $sql = "SELECT * FROM rooms WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function getAll($queryParams = [])
  {
    $sql = "SELECT * FROM rooms WHERE 1=1";
    $params = [];

    if (!empty($filters['guests'])) {
      $sql .= " AND max_guests >= ?";
      $params[] = $queryParams['guests'];
    }
    if (!empty($queryParams['minPrice'])) {
      $sql .= " AND price_per_night >= ?";
      $params[] = $queryParams['minPrice'];
    }
    if (!empty($queryParams['maxPrice'])) {
      $sql .= " AND price_per_night <= ?";
      $params[] = $queryParams['maxPrice'];
    }
    if (!empty($queryParams['fromRating'])) {
      $sql .= " AND rating >= ?";
      $params[] = $queryParams['fromRating'];
    }

    if (!empty($queryParams['sort'])) {
      switch ($queryParams['sort']) {
        case 'dsc-rating':
          $sql .= " ORDER BY rating DESC";
          break;
        case 'asc-rating':
          $sql .= " ORDER BY rating ASC";
          break;
        case 'dsc-price':
          $sql .= " ORDER BY price_per_night DESC";
          break;
        case 'asc-price':
          $sql .= " ORDER BY price_per_night ASC";
          break;
      }
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>