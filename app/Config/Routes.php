<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'UserController::index');
$routes->get('/testlog', 'Home::index');


$routes->group('api/v1', ['namespace' => 'App\Controllers'], function ($routes) {

    // Auth Routes
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/logout/', 'AuthController::logout');
    $routes->post('register', 'RegistrationController::createUser');

    $routes->group('post',['filter' => 'jsonInterceptor'], function ($routes) {
        // Product Routes
        $routes->post('createProduct', 'ProductController::createProduct');
        $routes->post('product/(:segment)/commentary', 'ProductController::addCommentary');

        // Contact Routes
        $routes->post('contact/sendrequest', 'ContactController::sendRequest');

        // Categories Routes
        $routes->post('categories', 'CategoriesController::addCategory');

        // Profile Routes
        $routes->post('profile', 'ProfileController::updateProfile');
    });

    // Subgroup for Get Routes
    $routes->group('get', function ($routes) {
        // Product Routes
        $routes->get('products', 'ProductController::getProducts');
        $routes->get('products/(:segment)', 'ProductController::getProduct/$1');
        $routes->get('product/(:segment)', 'ProductController::searchProduct/$1');
        $routes->get('product/(:segment)/commentary/(:segment)', 'ProductController::getCommentary/$1/$2');
        $routes->get('product/(:segment)/commentaries', 'ProductController::getCommentaries/$1');
        $routes->get('products/commentary/(:segment)', 'ProductController::getCommentaryById/$1');

        // Contact Routes
        $routes->get('contact/getrequest/(:segment)', 'ContactController::getRequest/$1');
        $routes->get('contact/getrequests', 'ContactController::getRequests');

        // Categories Routes
        $routes->get('categories/getCategory/(:segment)', 'CategoriesController::getCategory/$1');
        $routes->get('categories/getCategories', 'CategoriesController::getCategories');
        $routes->get('categories/product/(:segment)', 'CategoriesController::getProductCategories/$1');

        // Profile Routes
        $routes->get('profiles', 'ProfileController::getAllProfiles');
        $routes->get('profile/(:profileId)', 'ProfileController::getOneProfile');
        $routes->get('profile', 'ProfileController::getSelfProfile');
    });

});
