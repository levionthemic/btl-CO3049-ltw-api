<?php
require_once __DIR__ . '/../middlewares/ErrorHandlingMiddleware.php';
ErrorHandlingMiddleware::handleErrors();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../routes/api.php';

$uri = trim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];

// echo $_SERVER['REQUEST_URI'] . "<br>";

if (strpos($uri, 'public/api/') == 0) { //Example url: localhost/public/api/settings
  $uri = substr($uri, strlen('public/api/'));
  echo "Modified URI: $uri<br>";

  dispatch($uri, $method);
} else {
  http_response_code(404);
  echo json_encode(["error" => "Invalid API endpoint"]);
}
