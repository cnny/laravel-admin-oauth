<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'middleware'    => config('admin.route.middleware'),
    'namespace'     => 'Cann\Admin\OAuth\Controllers',
], function (Router $router) {

    $router->get('/oauth/authorize', 'AuthController@toAuthorize');

    $router->get('/oauth/callback', 'AuthController@oauthCallback');

    $router->get('/oauth/bind-account', 'AuthController@bindAccount');

    $router->post('/oauth/bind-account', 'AuthController@bindAccount');
});
