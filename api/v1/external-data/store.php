<?php

// header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

use Core\Database;
use Models\ExternalData;

$config = require base_path('config.php');

// instantiate db & connect
$database = new Database($config['database']);

// instantiate external data object
$externalData = new ExternalData($database);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse(405, 'Method Not Allowed');
  return;
} else {
  $url = 'https://jsonplaceholder.typicode.com/users/2/todos';
  $response = file_get_contents($url);

  if ($response === false) {
    sendResponse(500, 'Failed to retrieve external data');
    return;
  }

  // Process the response data
  $data = json_decode($response, true);

  $database = new Database($config['database']);

  $count = 0;

  // Store the data in the database
  $query = "INSERT INTO external_data (user_id, data) VALUES (:user_id, :data)";

  foreach ($data as $item) {
    
    $params = [
      'user_id' => $item['userId'],
      'data' => json_encode(
        [
          'id' => $item['id'],
          'title' => $item['title'],
          'completed' => $item['completed']
        ]
      )
    ];

    try {
      $database->insert($query, $params);
      $count++;
    } catch (PDOException $e) {
      // Handle the database error, if needed
      continue;
    }
  }

  sendResponse(200, $count . ' external data retrieved and stored successfully');
}