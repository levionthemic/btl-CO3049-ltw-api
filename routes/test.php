<?php

require_once __DIR__ . '/../controllers/HomeController.php';

function handleTestRoutes($uri)
{
  echo 'ok';
  if ($uri == '/api/test')
  {
    echo $uri;

  }
  // $homeController = new HomeController();
  // $homeController->index();
  return true;
}
