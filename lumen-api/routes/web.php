<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Authentication Routes
$router->group(['prefix' => 'api/auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me'); // Renamed from user-profile for consistency
});

// User CRUD Routes (Protected by Auth Middleware)
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('users', 'UserController@index');          // View all users
    $router->post('users', 'UserController@store');         // Create/Add user (can be admin only)
    $router->get('users/{id}', 'UserController@show');       // View a specific user
    $router->put('users/{id}', 'UserController@update');       // Edit a user
    $router->patch('users/{id}', 'UserController@update');    // Edit a user (alternative)
    $router->delete('users/{id}', 'UserController@destroy');   // Delete a user
});
