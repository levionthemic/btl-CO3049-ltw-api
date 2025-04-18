<?php
require_once __DIR__ . '/../utils/ApiError.php';

class ErrorHandlingMiddleware
{
  public static function handleErrors()
  {
    set_exception_handler(function ($exception) {
      if ($exception instanceof ApiError) {
        $statusCode = $exception->getStatusCode();
      } else {
        $statusCode = 500;
      }

      http_response_code($statusCode);
      echo json_encode([
        "status" => "error",
        "message" => $exception->getMessage(),
        "code" => $statusCode
      ]);
      exit();
    });

    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
      http_response_code(500);
      echo json_encode([
        "status" => "error",
        "message" => "A system error occurred",
        "details" => "$errstr in $errfile on line $errline"
      ]);
      exit();
    });

    register_shutdown_function(function () {
      $error = error_get_last();
      if ($error !== null) {
        http_response_code(500);
        echo json_encode([
          "status" => "error",
          "message" => "A fatal error occurred",
          "details" => "{$error['message']} in {$error['file']} on line {$error['line']}"
        ]);
        exit();
      }
    });
  }
}
