<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    $router->resource('banners', 'BannerController'); // 轮播图
    $router->resource('categories', 'CategoryController'); // 分类
    $router->resource('products', 'ProductController'); // 商品
    $router->resource('types', 'TypeController');   // 类型
    $router->get('orders', 'OrdersController@index')->name('admin.orders.index');
    $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show');
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');// 发货的接口
    $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('admin.orders.handle_refund');

//    $router->get('coupon_codes', 'CouponCodesController@index');// 优惠券
//    $router->post('coupon_codes', 'CouponCodesController@store');
//    $router->get('coupon_codes/create', 'CouponCodesController@create');
//    $router->get('coupon_codes/{id}/edit', 'CouponCodesController@edit');
//    $router->put('coupon_codes/{id}', 'CouponCodesController@update');
//    $router->delete('coupon_codes/{id}', 'CouponCodesController@destroy');
    $router->resource('coupon_codes', 'OrdersController');   // 优惠券

});
