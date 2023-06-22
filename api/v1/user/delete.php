<?php

// header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

use Core\Database;
use Models\User;

$config = require base_path('config.php');

// instantiate db & connect
$database = new Database($config['database']);

// instantiate user object
$user = new User($database);

// get raw posted data
$data = json_decode(file_get_contents("php://input"), true);

// validate data
if (!isset($data['id'])) {
  sendResponse(400, 'Missing required id');
  return;
}

$user->id = $data['id'];

try {
  $user->delete();
  sendResponse(200, 'User successfully deleted');
  return;
} catch (PDOException $e) {
  sendResponse(500, 'Failed to delete user', $e->getMessage());
  return;
}
