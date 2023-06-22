<?php

// header
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");

use Core\Database;
use Models\User;

$config = require base_path('config.php');

// instantiate db & connect
$database = new Database($config['database']);

// instantiate user object
$user = new User($database);

// get raw posted data
// $data = json_decode(file_get_contents("php://input"), true);

// validate data
if (!isset($_GET['id'])) {
  sendResponse(400, 'Missing required id');
  return;
}

try {
  $result = $user->findById($_GET['id']);
  if (!$result) {
    sendResponse(404, 'User not found');
    return;
  }
  sendResponse(200, 'OK', $result);
  return;
} catch (PDOException $e) {
  sendResponse(500, 'Failed to retrieve user');
  return;
}
