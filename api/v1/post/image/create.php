<?php

// header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

use Core\Database;
use Models\Post;
use Models\Image;

$config = require base_path('config.php');

// instantiate db & connect
$database = new Database($config['database']);

// instantiate post object
$post = new Post($database);

// get raw posted data
$data = json_decode(file_get_contents("php://input"), true);

// validate data
if (!isset($data['post_id']) || !isset($data['image_path'])) {
  sendResponse(400, 'Missing required data');
  return;
}

try {
  $result = $post->findById($data['post_id']);

  if (!$result) {
    sendResponse(404, 'Post not found');
    return;
  }

  $image = new Image($database);

  $image->imageable_id = $result['id'];
  $image->imageable_type = 'Post';
  $image->image_path = $data['image_path'];

  $image->create();

  sendResponse(200, 'Image successfully stored');
  return;
} catch (PDOException $e) {
  sendResponse(500, 'Failed to store image');
  return;
}
