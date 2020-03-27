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

    $router->get('orders', 'OrdersController@index')->name('admin.orders.index');//订单列表
    $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show');//查看订单详情
    $router->get('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship'); //订单配送
    $router->get('delivery', 'OrdersController@deliveryorder')->name('admin.orders.delivery'); //订单配送


    $router->get('routes', 'RouteController@index')->name('admin.routes.index');//路线列表
    $router->get('routes/create', 'RouteController@create')->name('admin.routes.create');//添加路线列表
    $router->post('routes', 'RouteController@store');
    // $router->delete('routes/{id}', 'RouteController@remove')->name('admin.route.remove');//商品从购物车中删除

    $router->get('types', 'TypeController@index')->name('admin.types.index');//商品类型列表
    $router->get('types/create', 'TypeController@create')->name('admin.types.create');//添加商品类别
    $router->post('types', 'TypeController@store');
    $router->get('types/{id}/edit', 'TypeController@edit');//编辑商品类别
    $router->put('types/{id}', 'TypeController@update');


});
