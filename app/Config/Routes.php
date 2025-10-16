<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ... otras rutas

$routes->get('/pdf', 'FileController::index');
$routes->post('/pdf/upload', 'FileController::upload');

$routes->get('/pdf/view/(:segment)', 'FileController::view/$1');
$routes->get('/pdf/download/(:segment)', 'FileController::download/$1');

// ...
