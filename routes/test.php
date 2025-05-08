<?php

require_once __DIR__ . '/../controllers/HomeController.php';

function handleTestRoutes($uri, $method)
{
  if ($uri === '/test' && $method === 'GET') {
    return true;
  }
  return false;
}
