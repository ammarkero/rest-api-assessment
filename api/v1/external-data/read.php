<?php

// header
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");

// Get external data
$url = 'https://jsonplaceholder.typicode.com/users/1/todos';
$response = @file_get_contents($url);

if ($response === false) {
  sendResponse(500, 'Failed to retrieve external data');
  return;
}

// Process the response data
$data = json_decode($response, true);

sendResponse(200, 'External data retrieved successfully', $data);
return;




