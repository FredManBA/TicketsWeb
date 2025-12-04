<?php

use App\Core\Router;

session_start();

require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Controller.php';
require __DIR__ . '/../app/Core/Model.php';
require __DIR__ . '/../app/Core/View.php';

// Load environment variables
$env = parse_ini_file(__DIR__ . '/../.env');

// Debug mode configuration
if (isset($env['DEBUG']) && filter_var($env['DEBUG'], FILTER_VALIDATE_BOOLEAN)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Database connection
require_once __DIR__ . '/../config/database.php';

// Simple Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$router = new Router();

// Define routes
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/vehicles', 'VehicleController@index');
$router->add('GET', '/vehicles/create', 'VehicleController@create');
$router->add('POST', '/vehicles/store', 'VehicleController@store');
$router->add('GET', '/vehicles/edit/{id}', 'VehicleController@edit');
$router->add('POST', '/vehicles/update/{id}', 'VehicleController@update');
$router->add('GET', '/vehicles/delete/{id}', 'VehicleController@delete');

// Brand Routes
$router->add('GET', '/brands', 'BrandController@index');
$router->add('GET', '/brands/create', 'BrandController@create');
$router->add('POST', '/brands/store', 'BrandController@store');
$router->add('GET', '/brands/edit/{id}', 'BrandController@edit');
$router->add('POST', '/brands/update/{id}', 'BrandController@update');
$router->add('GET', '/brands/delete/{id}', 'BrandController@delete');

// Owner Routes
$router->add('GET', '/owners', 'OwnerController@index');
$router->add('GET', '/owners/create', 'OwnerController@create');
$router->add('POST', '/owners/store', 'OwnerController@store');
$router->add('GET', '/owners/edit/{id}', 'OwnerController@edit');
$router->add('POST', '/owners/update/{id}', 'OwnerController@update');
$router->add('GET', '/owners/delete/{id}', 'OwnerController@delete');

// Auth Routes
$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@authenticate');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@store');
$router->add('GET', '/logout', 'AuthController@logout');

// Profile Routes
$router->add('GET', '/profile', 'ProfileController@edit');
$router->add('POST', '/profile/update', 'ProfileController@update');

try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
