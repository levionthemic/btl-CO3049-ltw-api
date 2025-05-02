<?php

require_once __DIR__ . '/../controllers/UserController.php';

function handleUserRoutes($uri, $method)
{
  $userController = new UserController();

  if ($uri === '/admin/users' && $method === 'GET') {
    $userController->getAllUsers();
    return true;
  }

  if (preg_match('/admin\/user\/delete\/(\d+)/', $uri, $matches) && $method === 'DELETE') {
    $id = $matches[1];
    $userController->deleteUser($id);
    return true;
  }

  if ($uri === '/user/update' && $method === 'POST') {
    $userController->updateUser();
    return true;
  }

  if ($uri === '/admin/user/edit' && $method === 'POST') {
    $userController->editUser();
    return true;
  }

  return false;
}
