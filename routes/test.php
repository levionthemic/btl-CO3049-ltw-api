<?php

require_once __DIR__ . '/../controllers/HomeController.php';

function handleTestRoutes($uri)
{
  if ($uri == '/test') {
    echo 'ok';
    return true;

  }
  // $homeController = new HomeController();
  // $homeController->index();
  return false;
}
