<?php

// header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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
if (!isset($data['id']) || !isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
  sendResponse(400, 'Missing required data');
  return;
}

$user->id = $data['id'];
$user->name = $data['name'];
$user->email = $data['email'];
$user->password = $data['password'];

try {
  $result = $user->update();
  sendResponse(200, 'User successfully updated', $result);
  return;
} catch (PDOException $e) {
  sendResponse(500, 'Failed to update user');
  return;
}
