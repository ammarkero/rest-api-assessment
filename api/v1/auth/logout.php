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
use Firebase\JWT\Key;

$config = require base_path('config.php');

try {
  $token = getBearerToken();
  $key = $config['services']['jwt']['secret_key'];
  
  $decoded = JWT::decode($token, new Key($key, 'HS256'));

  $database = new Database($config['database']);
  $user = new User($database);

  $user = $user->findById($decoded->sub);

  if(!is_array($user)) {
    sendResponse(404, 'User not found');
  }

  $userLog = new UserLog($database);

  $userLog->user_id = $user['id'];
  $userLog->token = $token;
  $userLog->update();

  sendResponse(200, 'User logged out successfully');

} catch (Exception $e) {
  sendResponse(401, 'Access Token is Invalid', ['error' => $e->getMessage()]);;
}

function getAuthorizationHeader(){
  $headers = null;

  $allheaders = getallheaders();

  if (isset($allheaders['Authorization'])) {
    $headers = trim($allheaders['Authorization']);
  }

  return $headers;
}

function getBearerToken() {

  $headers = getAuthorizationHeader();

  if (!empty($headers)) {
      if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
          return $matches[1];
      }
  }
  
  sendResponse(401, 'Access Token Not found');
}