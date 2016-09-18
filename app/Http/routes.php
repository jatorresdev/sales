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

        // Publication
        $app->get('publication', [
            'uses' => 'PublicationController@index',
            'as' => 'showPublications'
        ]);

        $app->get('publication/{id}', [
            'uses' => 'PublicationController@show',
            'as' => 'showPublication'
        ]);

        $app->post('publication', [
            'uses' => 'PublicationController@store',
            'as' => 'savePublication'
        ]);

        $app->put('publication/{id}', [
            'uses' => 'PublicationController@update',
            'as' => 'updatePublication'
        ]);

        $app->delete('publication/{id}', [
            'uses' => 'PublicationController@destroy',
            'as' => 'destroyPublication'
        ]);

    });