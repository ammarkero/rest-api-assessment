<?php
// helpers.php

// Send a JSON response with the appropriate status code
function sendResponse($statusCode, $message, $data = []) {
  header('Content-Type: application/json');
  http_response_code($statusCode);

  $response = [
    'status' => $statusCode,
    'message' => $message,
    'data' => $data
  ];

  echo json_encode($response);
  exit();
}
