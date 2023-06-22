<?php

const BASE_PATH = __DIR__ . '/../';

date_default_timezone_set('Asia/Kuala_Lumpur');
ini_set("log_errors", 1);
ini_set("error_log", BASE_PATH . 'tmp/php-error.log');

require BASE_PATH . 'Core/functions.php';


spl_autoload_register(function ($class) {
  $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
  require base_path("{$class}.php");
});

require base_path('vendor/autoload.php');
require base_path('Core/helpers.php');

// $client = new Predis\Client();
// $client->set('foo', 'bar');
// $value = $client->get('foo');

// dd($value);
// die();

$router = new \Core\Router();
$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
