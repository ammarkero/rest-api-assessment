<?php

return [
  'database' => [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'digitas_db',
    'charset' => 'utf8mb4'
  ],
  'services' => [
    'jwt' => [
      'secret_key' => '07jCIM2rwtfCW277',
      'expiry_time' => 5 * 60, // Expiry time in seconds (5 minutes)
    ]
  ]
];