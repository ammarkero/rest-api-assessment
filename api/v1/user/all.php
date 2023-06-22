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

try {
  $result = $user->getAllUsers();

  // get row count
  $userCount = count($result);

  if ($userCount == 0) {
    sendResponse(404, 'No users found');
    return;
  } else {
    sendResponse(200, 'OK', $result);
    return;
  }
} catch (PDOException $e) {
  sendResponse(500, 'Failed to retrieve users');
  return;
}


