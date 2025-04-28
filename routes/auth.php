<?php

require_once __DIR__ . '/../controllers/AuthController.php';

function handleAuthRoutes($uri, $method)
{
  $authController = new AuthController();

  if ($uri === '/auth/login' && $method === 'POST') {
    $authController->login();
    return true;
  }

  if ($uri === '/auth/register' && $method === 'POST') {
    $authController->register();
    return true;
  }

  if ($uri === '/auth/logout' && $method === "DELETE") {
    $authController->logout();
    return true;
  }

  if ($uri === '/auth/refresh-token' && $method === 'GET') {
    $authController->refreshToken();
    return true;
  }

  return false;
}
