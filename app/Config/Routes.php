<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->post('/api/v1/auth/login', 'AuthController::login');
$routes->post('/api/v1/register', 'RegistrationController::createUser');
$routes->post('/api/v1/auth/logout/', 'AuthController::logout');

$routes->get('/', 'UserController::index');
$routes->get('/testlog', 'Home::index');
