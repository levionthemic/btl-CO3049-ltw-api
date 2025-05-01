<?php

require_once __DIR__ . '/../controllers/UserController.php';

function handleUserRoutes($uri, $method)
{
  $userController = new UserController();

  // if ($uri === 'users' && $method === 'GET') {
  //   $userController->getAllUsers();
  //   return true;
  // }

  // if (preg_match('/users\/(\d+)/', $uri, $matches) && $method === 'GET') {
  //   $userId = $matches[1];
  //   $userController->getUserById($userId);
  //   return true;
  // }

  if ($uri === '/user/update' && $method === 'PUT') {
    $userController->updateUser();
    return true;
  }

  return false;
}
