<?php
header('Content-Type: application/json');

require_once './config/database.php';
require_once './controllers/UserController.php';

// Hanling errors
require_once './middlewares/ErrorHandlingMiddleware.php';
ErrorHandlingMiddleware::handleErrors();

// Load ENV
require_once './config/environment.php';
loadEnv();

// Config CORS
require_once './config/cors.php';

// Use API routes
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$uri = str_replace($basePath, '', $uri);

require_once './routes/api.php';
dispatch($uri, $method);
