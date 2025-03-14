<?php

require_once __DIR__ . '/../controllers/HomeController.php';

function handleTestRoutes()
{
  $homeController = new HomeController();
  $homeController->index();
  return true;
}
