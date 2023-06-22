<?php

$router->get('/api/v1/user/all', 'api/v1/user/all.php');
$router->get('/api/v1/user/single', 'api/v1/user/single.php');
$router->post('/api/v1/user/create', 'api/v1/user/create.php');
$router->put('/api/v1/user/update', 'api/v1/user/update.php');
$router->delete('/api/v1/user/delete', 'api/v1/user/delete.php');
$router->get('/api/v1/user/role', 'api/v1/user/role/read.php');
$router->post('/api/v1/user/role/create', 'api/v1/user/role/create.php');


$router->get('/api/v1/external-data/read', 'api/v1/external-data/read.php');
$router->post('/api/v1/external-data/store', 'api/v1/external-data/store.php');

$router->post('/api/v1/auth/login', 'api/v1/auth/login.php');
$router->post('/api/v1/auth/logout', 'api/v1/auth/logout.php');

$router->get('/api/v1/post/image', 'api/v1/post/image/read.php');
$router->post('/api/v1/post/image/store', 'api/v1/post/image/create.php');