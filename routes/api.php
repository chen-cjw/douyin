<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['serializer:array']
], function ($api) {

    $api->get('/banners', 'BannersController@index'); // 轮播图
    $api->get('/types', 'TypesController@index'); // 类型
    //$api->get('/types/{type_id}/categories', 'TypesController@index'); // 类型下分类
    $api->get('/categories', 'CategoriesController@index'); // 分类
    //$api->get('/categories/{category_id}', 'CategoriesController@show'); // 分类详情
    $api->get('/categories/{category_id}/types/{type_id}/products', 'ProductsController@index'); // 分类下商品 (佣金排序 | 领样人数排序 | 发品时间排序 | 价格排序
    $api->get('/products/{product_id}', 'ProductsController@show'); // 商品详情

});
