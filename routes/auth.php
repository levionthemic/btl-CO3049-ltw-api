<?php

require_once __DIR__ . '/../controllers/AuthController.php';

function handleAuthRoutes($uri, $method)
{
  $authController = new AuthController();

  if ($uri === 'login' && $method === 'POST') {
    $authController->login();
    return true;
  }

  if ($uri === 'register' && $method === 'POST') {
    $authController->register();
    return true;
  }

  return false;
}
