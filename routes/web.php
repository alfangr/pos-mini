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

        $router->get('user', ['as' => 'allUser', 'uses' => 'UserController@index']);
        $router->post('user', ['as' => 'createUser', 'uses' => 'UserController@store']);
        $router->get('user/{id}', ['as' => 'showUser', 'uses' => 'UserController@show']);
        $router->put('user/{id}', ['as' => 'updateUser', 'uses' => 'UserController@update']);
        $router->delete('user/{id}', ['as' => 'destroyUser', 'uses' => 'UserController@destroy']);

        $router->get('merchant', ['as' => 'allMerchant', 'uses' => 'MerchantController@index']);
        $router->post('merchant', ['as' => 'createMerchant', 'uses' => 'MerchantController@store']);
        $router->get('merchant/{id}', ['as' => 'showMerchant', 'uses' => 'MerchantController@show']);
        $router->put('merchant/{id}', ['as' => 'updateMerchant', 'uses' => 'MerchantController@update']);
        $router->delete('merchant/{id}', ['as' => 'destroyMerchant', 'uses' => 'MerchantController@destroy']);

        $router->get('outlet', ['as' => 'allOutlet', 'uses' => 'OutletController@index']);
        $router->post('outlet', ['as' => 'createOutlet', 'uses' => 'OutletController@store']);
        $router->get('outlet/{id}', ['as' => 'showOutlet', 'uses' => 'OutletController@show']);
        $router->put('outlet/{id}', ['as' => 'updateOutlet', 'uses' => 'OutletController@update']);
        $router->delete('outlet/{id}', ['as' => 'destroyOutlet', 'uses' => 'OutletController@destroy']);

        $router->get('product', ['as' => 'allProduct', 'uses' => 'ProductController@index']);
        $router->post('product', ['as' => 'createProduct', 'uses' => 'ProductController@store']);
        $router->get('product/{id}', ['as' => 'showProduct', 'uses' => 'ProductController@show']);
        $router->put('product/{id}', ['as' => 'updateProduct', 'uses' => 'ProductController@update']);
        $router->delete('product/{id}', ['as' => 'destroyProduct', 'uses' => 'ProductController@destroy']);
    });
});
