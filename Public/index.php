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

// Database class (connection se obtiene en los modelos)
require_once __DIR__ . '/../Config/database.php';

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

// Ticket Routes
$router->add('GET', '/tickets', 'TicketController@index');
$router->add('GET', '/tickets/{id}', 'TicketController@show');
$router->add('POST', '/tickets', 'TicketController@store');
$router->add('POST', '/tickets/{id}/update', 'TicketController@update');
$router->add('POST', '/tickets/{id}/delete', 'TicketController@delete');

// Ticket entries (comentarios)
$router->add('GET', '/tickets/{id}/entries', 'EntryController@index');
$router->add('POST', '/tickets/{id}/entries', 'EntryController@store');

// Type Routes
$router->add('GET', '/types', 'TypeController@index');
$router->add('GET', '/types/{id}', 'TypeController@show');
$router->add('POST', '/types', 'TypeController@store');
$router->add('POST', '/types/{id}/update', 'TypeController@update');
$router->add('POST', '/types/{id}/delete', 'TypeController@delete');

// Status Routes
$router->add('GET', '/status', 'StatusController@index');
$router->add('GET', '/status/{id}', 'StatusController@show');
$router->add('POST', '/status', 'StatusController@store');
$router->add('POST', '/status/{id}/update', 'StatusController@update');
$router->add('POST', '/status/{id}/delete', 'StatusController@delete');

// Role Routes
$router->add('GET', '/roles', 'RoleController@index');
$router->add('GET', '/roles/{id}', 'RoleController@show');
$router->add('POST', '/roles', 'RoleController@store');
$router->add('POST', '/roles/{id}/update', 'RoleController@update');
$router->add('POST', '/roles/{id}/delete', 'RoleController@delete');

// User Routes
$router->add('GET', '/users', 'UserController@index');
$router->add('GET', '/users/{id}', 'UserController@show');
$router->add('POST', '/users', 'UserController@store');
$router->add('POST', '/users/{id}/update', 'UserController@update');
$router->add('POST', '/users/{id}/delete', 'UserController@delete');

// Transition Routes
$router->add('GET', '/transitions', 'TransitionController@index');
$router->add('POST', '/transitions', 'TransitionController@store');
$router->add('POST', '/transitions/{id}/delete', 'TransitionController@delete');

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
