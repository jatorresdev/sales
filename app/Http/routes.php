<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers'],
    function () use ($app) {
        $app->post('user', [
            'uses' => 'UserController@store',
            'as' => 'saveUser'
        ]);

        $app->put('user', [
            'uses' => 'UserController@update',
            'as' => 'updateUser'
        ]);

        $app->post('user/login', [
            'uses' => 'UserController@login',
            'as' => 'loginUser'
        ]);

        $app->get('user/logout', [
            'uses' => 'UserController@logout',
            'as' => 'logoutUser'
        ]);
    });

//$app->group(['namespace' => 'App\Http\Controllers'] , function($app){
//    $api = 'api';
//    $app->get($api.'/', ['uses' => 'PublicationController@getPublications', 'as' => 'allPublications']);
//
//    $app->get($api.'/publication/{id}', [
//        'uses' => 'PublicationController@getPublication',
//        'as' => 'singlePublication',
//        'middleware' => 'auth'
//    ]);
//
//    $app->post($api.'/publication', ['uses' => 'PublicationController@savePublication', 'as' => 'savePublication']);
//    $app->put($api.'/publication/{id}', ['uses' => 'PublicationController@updatePublication', 'as' => 'updatePublication']);
//    $app->delete($api.'/publication/{id}', ['uses' => 'PublicationController@deletePublication', 'as' => 'deletePublication']);
//});
