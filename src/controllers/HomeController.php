<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../utils/ApiError.php';

class HomeController
{
  public function index()
  {
    try {
      echo json_encode(["message" => "Welcome to my API"]);
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
