<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','LoginController@login')->name('login'); //用户登录页面
Route::get('town','ApiController@town');//所属县、乡镇联动api


//前台（客户）路由部分（无需权限判断）
Route::prefix('user')->group(function (){
    Route::post('check','LoginController@check');   //用户登录认证
    Route::get('logout','LoginController@logout');   //退出登录路由

});
Route::prefix('user')->middleware('auth:customer')->group(function (){  //需权限判断
    Route::get('products/sort/{sort?}', 'ProductsController@index')->name('products.index');   //用户（商品）首页
    Route::get('products/{product}', 'ProductsController@show')->name('products.show'); //商品详情页

    Route::get('edit','CustomersController@edit');  //用户修改个人信息页面
    Route::put('update','CustomersController@update')->name('user.update'); //用户修改操作
    Route::get('town/{id}','CustomersController@town');//获取该县下的乡镇

    Route::get('pages','PagesController@root');

    Route::post('cart', 'CartController@add')->name('cart.add'); //商品添加至购物车
    Route::get('cart', 'CartController@index')->name('cart.index'); //查看购物车
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');//商品从购物车中删除

    Route::post('orders', 'OrdersController@store')->name('orders.store');//生成订单
    Route::get('orders', 'OrdersController@index')->name('orders.index');//订单页面
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');//订单详情

    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
});
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');

