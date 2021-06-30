<?php

use \Illuminate\Support\Str;
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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    // API Version 1
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->post('register', ['as' => 'register', 'uses' => 'AuthController@register']);
        $router->post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    });

    // route auth required
    $router->group(['middleware' => 'auth','prefix' => 'v1'], function ($router) {
        $router->get('profile', ['as' => 'profile', 'uses' => 'AuthController@profile']);

        $router->get('user', ['as' => 'user', 'uses' => 'UserController@index']);
        $router->post('user', ['as' => 'user', 'uses' => 'UserController@store']);
        $router->get('user/{id}', ['as' => 'user', 'uses' => 'UserController@show']);
        $router->put('user/{id}', ['as' => 'user', 'uses' => 'UserController@update']);
        $router->delete('user/{id}', ['as' => 'user', 'uses' => 'UserController@destroy']);

        $router->get('product', ['as' => 'product', 'uses' => 'ProductController@index']);
        $router->post('product', ['as' => 'product', 'uses' => 'ProductController@store']);
        $router->get('product/{id}', ['as' => 'product', 'uses' => 'ProductController@show']);
        $router->put('product/{id}', ['as' => 'product', 'uses' => 'ProductController@update']);
        $router->delete('product/{id}', ['as' => 'product', 'uses' => 'ProductController@destroy']);
    });
});
