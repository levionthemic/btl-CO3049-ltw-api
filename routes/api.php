<?php

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/room.php';
require_once 'faq.php';
require_once 'settings.php';
require_once 'contacts.php';
require_once 'blog.php';
require_once 'comment.php';

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

function dispatch($uri, $method)
{
  $uri = str_replace('/api', '', $uri);

  if (handleAuthRoutes($uri, $method)) return;
  if (handleTestRoutes($uri, $method)) {
    AuthMiddleware::getInstance()->authenticate();
    return;
  }
  if (handleUserRoutes($uri, $method)) 
  {
    AuthMiddleware::getInstance()->authenticate();
    return;
  }
  if (handleRoomRoutes($uri, $method)) return;
  if (handleFaqRoutes($uri, $method)) return;
  
  if (handleSettingsRoutes($uri, $method)) return;
  if (handleBlogRoutes($uri, $method)) return; 
  if (handleCommentRoutes($uri, $method)) return; 
  if (handleContactsRoutes($uri, $method)) return;
  

  http_response_code(404);
  echo json_encode(["error" => "API route not found"]);
}
