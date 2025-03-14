<?php

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/test.php';

function dispatch($uri, $method)
{
  if (handleAuthRoutes($uri, $method)) return;
  if (handleUserRoutes($uri, $method)) return;
  if (handleTestRoutes()) return;

  http_response_code(404);
  echo json_encode(["error" => "API route not found"]);
}
