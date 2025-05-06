<?php

require_once __DIR__ . '/../controllers/SettingsController.php';

function handleSettingsRoutes($uri, $method)
{

  $settingsController = new SettingsController();

  if ($uri === '/settings' && $method === 'GET') {
    $settingsController->showForm();
    return true;
  }

  if ($uri === '/settings' && $method === 'POST') {
    $settingsController->saveSettings();
    return true;
  }

  if ($uri === '/settings/random' && $method === 'GET') {
    $settingsController->getRandom();
    return true;
  }

  if ($uri === '/settings/latest' && $method === 'GET') {
    $settingsController->getLatest();
    return true;
  }



  return false;
}