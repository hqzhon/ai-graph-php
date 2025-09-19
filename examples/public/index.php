<?php

use App\Examples\Http\Request;
use App\Examples\Routing\Router;

// Bootstrap the application
$container = require __DIR__ . '/../bootstrap.php';

// Handle the request
$request = new Request();
$router = $container->get(Router::class);

$response = $router->handle($request);
$response->send();
