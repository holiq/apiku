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

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    // Authentication route group
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', 'AuthController@register');
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
    });
    
    $router->group(['prefix' => 'admin', 'middleware' => ['auth:api', 'role:super-admin|admin']], function () use ($router) {
        $router->get('/', 'AdminController@index');
        
        // Permissions route group
        $router->group(['prefix' => 'permissions'], function () use ($router) {
            $router->get('/', 'PermissionController@index');
            $router->post('/', 'PermissionController@store');
            $router->get('/{id}', 'PermissionController@show');
            $router->put('/{id}', 'PermissionController@update');
            $router->delete('/{id}', 'PermissionController@destroy');
        });
        
        // Roles route group
        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('/', 'RoleController@index');
            $router->post('/', 'RoleController@store');
            $router->get('/{id}', 'RoleController@show');
            $router->put('/{id}', 'RoleController@update');
            $router->delete('/{id}', 'RoleController@destroy');
        });
        
        // Users route group
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UserController@index');
            $router->post('/', 'UserController@store');
            $router->get('/{id}', 'UserController@show');
            $router->put('/{id}', 'UserController@update');
            $router->delete('/{id}', 'UserController@destroy');
        });
    });
});
