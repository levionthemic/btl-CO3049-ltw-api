<?php
require_once __DIR__ . '/../middlewares/ErrorHandlingMiddleware.php';
ErrorHandlingMiddleware::handleErrors();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../routes/api.php';

$uri = trim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];

if (strpos($uri, 'api/') === 0) {
  $uri = substr($uri, 4);
  dispatch($uri, $method);
} else {
  http_response_code(404);
  echo json_encode(["error" => "Invalid API endpoint"]);
}
