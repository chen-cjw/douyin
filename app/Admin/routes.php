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

});
