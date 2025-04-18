<?php

// require_once __DIR__ . '/auth.php';
// require_once __DIR__ . '/user.php';
require_once __DIR__ . '/test.php';
require_once 'settings.php';
require_once 'contacts.php';

function dispatch($uri, $method)
{

  // echo "URI: $uri - Method: $method\n";
  // if (handleAuthRoutes($uri, $method)) return;
  // if (handleUserRoutes($uri, $method)) return;
  if (handleTestRoutes($uri)) return;
  if (handleSettingsRoutes($uri, $method)) return;

  if (handleContactsRoutes($uri, $method)) return;

  http_response_code(404);
  echo json_encode(["error" => "API route not found"]);
}
