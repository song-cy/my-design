<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('auth/login', 'AuthController@getLogin');//后台登录页面

    $router->get('/', 'HomeController@index')->name('admin.home');  //后台首页

    $router->get('users', 'CustomersController@index');    //后台客户列表
    $router->get('users/create', 'CustomersController@create');  //新建客户信息路由
    $router->post('users', 'CustomersController@store');
    $router->get('users/{id}/edit', 'CustomersController@edit');  //编辑客户信息
    $router->put('users/{id}', 'CustomersController@update');


    $router->get('products', 'ProductsController@index');//后台商品列表
    $router->get('products/create', 'ProductsController@create');//添加商品信息
    $router->post('products', 'ProductsController@store');
    $router->get('products/{id}/edit', 'ProductsController@edit');//编辑商品
    $router->put('products/{id}', 'ProductsController@update');
    // $router->get('products/{id}', 'ProductsController@show');

});
