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

    $router->get('orders/pending', 'OrdersController@index')->name('admin.orders.index');//待处理订单列表
    $router->get('orders/pending/{order}', 'OrdersController@show')->name('admin.orders.show');//查看待处理订单详情
    $router->get('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship'); //处理订单

    $router->get('orders/delivery', 'OrdersController@delivery')->name('admin.orders.delivery'); //待配送订单列表
    $router->get('orders/delivery/{order}', 'OrdersController@show')->name('admin.delivery.show'); //查看待配送订单详情

    $router->get('orders/finished', 'OrdersController@finished')->name('admin.orders.finished'); //已完成订单列表
    $router->get('orders/finished/{order}', 'OrdersController@show')->name('admin.finished.show'); //查看已完成订单详情

    $router->get('orders/refund', 'OrdersController@refund')->name('admin.orders.refund'); //客户申请退货订单列表
    $router->get('orders/refund/{order}', 'OrdersController@show')->name('admin.refund.show'); //查看已完成订单详情
    $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('admin.orders.handle_refund');//处理退货

    $router->post('orders/{order}/exchange', 'OrdersController@handleExchange')->name('admin.orders.handle_exchange');//处理换货



    $router->get('routes', 'RouteController@index')->name('admin.routes.index');//路线列表
    $router->get('routes/create', 'RouteController@create')->name('admin.routes.create');//添加路线列表
    $router->post('routes', 'RouteController@store');
    // $router->delete('routes/{id}', 'RouteController@remove')->name('admin.route.remove');//商品从购物车中删除

    $router->get('types', 'TypeController@index')->name('admin.types.index');//商品类型列表
    $router->get('types/create', 'TypeController@create')->name('admin.types.create');//添加商品类别
    $router->post('types', 'TypeController@store');
    $router->get('types/{id}/edit', 'TypeController@edit');//编辑商品类别
    $router->put('types/{id}', 'TypeController@update');

    $router->get('finances', 'FinancesController@index');//资金明细列表
    $router->get('finances/create', 'FinancesController@create');//创建资金记录
    $router->post('finances', 'FinancesController@store');
    $router->get('finances/{id}/edit', 'FinancesController@edit');//编辑商品
    $router->put('finances/{id}', 'FinancesController@update');
    $router->get('finances/{id}', 'FinancesController@show');

});
