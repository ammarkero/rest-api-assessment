<?php

// header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

use Core\Database;
use Models\User;
use Models\UserLog;
use Firebase\JWT\JWT;

$config = require base_path('config.php');

// Get the request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($data['email']) || empty($data['password'])) {
  sendResponse(400, 'Missing required fields');
  return;
}

$database = new Database($config['database']);
$user = new User($database);

try {
  $user = $user->findByEmail($data['email']);
  // $user = $database->fetch($query, $params);
  if (!$user) {
    sendResponse(404, 'User not found');
    return;
  }
} catch (PDOException $e) {
  sendResponse(500, 'Failed to retrieve user');
  return;
}

// Check if the password is correct
if (!password_verify($data['password'], $user['password'])) {
  sendResponse(400, 'Incorrect password');
  return;
}

$payload = [
  'iss' => $_SERVER['HTTP_HOST'],
  'sub' => $user['id'],
  'iat' => time(),
  'exp' => time() + $config['services']['jwt']['expiry_time'],
];

// Generate JWT token
$token = JWT::encode($payload, $config['services']['jwt']['secret_key'], 'HS256');

$userLog = new UserLog($database);
$userLog->user_id = $user['id'];
$userLog->token = $token;

try {
  $userLog->create();
} catch (PDOException $e) {
  sendResponse(500, 'Failed to create user log');
  return;
}

sendResponse(200, 'User access token generated.', ['token' => $token]);

return;

