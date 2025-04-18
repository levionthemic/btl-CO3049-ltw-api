<?php

require_once 'environment.php';
loadEnv();

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

$options = [
  PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/../ca.pem',
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $conn = new PDO(
    "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
    $username,
    $password,
    $options
  );
  echo json_encode(["message" => "DB Connection Successful!"]);
} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "DB Connection failed: " . $e->getMessage()
  ]);
  exit;
}
