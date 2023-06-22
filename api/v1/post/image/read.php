<?php

// header
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");

use Core\Database;
use Models\Post;

$config = require base_path('config.php');

// instantiate db & connect
$database = new Database($config['database']);

// instantiate post object
$post = new Post($database);

// validate data
if (!isset($_GET['id'])) {
  sendResponse(400, 'Missing required id');
  return;
}

try {
  $result = $post->findById($_GET['id']);

  if (!$result) {
    sendResponse(404, 'Post not found');
    return;
  }

  $post->id = $result['id'];

  $image = $post->image();

  $imageCount = count($image);

  if ($imageCount == 0) {
    sendResponse(404, 'No post image found');
    return;
  } else {
    sendResponse(200, 'OK', $image);
    return;
  }
} catch (PDOException $e) {
  sendResponse(500, 'Failed to retrieve post image');
  return;
}


