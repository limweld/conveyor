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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->get('conveyor',function() use ($router) {
//     return view('conveyor/main');
// });


$router->get('/',function() use ($router) {
    return view('conveyor/login');
});

$router->get('login',function() use ($router) {
    return view('conveyor/login');
});

$router->get('main',function() use ($router) {
    return view('conveyor/main');
});

$router->get('generator',function() use ($router) {
    return view('conveyor/generator');
});

$router->group(['prefix' => 'barcode'], function() use ($router) {
    $router->get('read/batch/{id}','conveyor\BarcodeController@read');
});


$router->group(['prefix' => 'conveyor'], function() use ($router) {

    $router->group(['prefix' => 'api/v1'], function() use ($router) {

        $router->group(['prefix' => 'barcode'], function() use ($router) {
            $router->post('create','conveyor\BarcodeController@create');
            $router->post('read','conveyor\BarcodeController@read');
            $router->post('update','conveyor\BarcodeController@update');
            $router->post('delete','conveyor\BarcodeController@delete');
        });

        $router->group(['prefix' => 'dashboard'], function() use ($router) {
            $router->post('create','conveyor\DashboardController@create');
            $router->post('read','conveyor\DashboardController@read');
            $router->get('read/quota','conveyor\DashboardController@read_quota');
            $router->get('read/error','conveyor\DashboardController@read_error');
            $router->post('update','conveyor\DashboardController@update');
            $router->post('delete','conveyor\DashboardController@delete');
        });

        $router->group(['prefix' => 'generator'], function() use ($router) {
            $router->post('create','conveyor\GeneratorController@create');

            $router->post('read/batch','conveyor\GeneratorController@read_batch');
            $router->post('read/scanned','conveyor\GeneratorController@read_scanned');
            $router->post('read/unscanned','conveyor\GeneratorController@read_unscanned');
            
            $router->post('read/batch/count','conveyor\GeneratorController@read_batch_count');
            $router->post('read/scanned/count','conveyor\GeneratorController@read_scanned_count');
            $router->post('read/unscanned/count','conveyor\GeneratorController@read_unscanned_count');
            
            $router->post('update','conveyor\GeneratorController@update');
            
            $router->post('delete','conveyor\GeneratorController@delete');
        });

        $router->group(['prefix' => 'auth'], function() use ($router) {
            $router->post('login','AuthenticationController@login');
            $router->get('logout','AuthenticationController@logout');
        });

    });

});


$router->get('kill','AuthenticationController@kill');



