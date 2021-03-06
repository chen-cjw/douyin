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

    $api->get('auth','AuthController@index')->name('api.auth.index');
    $api->post('auth','AuthController@store')->name('api.auth.store');

    // 个人信息
    $api->get('meShow','AuthController@meShow')->name('api.auth.meShow');
    // 退出
    $api->delete('auth/current', 'AuthController@destroy')->name('api.auth.destroy');

    $api->get('banners', 'BannersController@index'); // 轮播图
    $api->get('types', 'TypesController@index'); // 类型
    //$api->get('/types/{type_id}/categories', 'TypesController@index'); // 类型下分类
    $api->get('categories', 'CategoriesController@index'); // 分类
    //$api->get('/categories/{category_id}', 'CategoriesController@show'); // 分类详情
    $api->get('categories/{category_id}/types/{type_id}/products', 'ProductsController@index'); // 分类下商品 (佣金排序 | 领样人数排序 | 发品时间排序 | 价格排序
    $api->get('products/{product_id}', 'ProductsController@show'); // 商品详情


    // 必须登陆
    $api->group(['middleware' => ['auth:api','bindings']], function ($api) {
        // 用户地址
        $api->get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
        $api->post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
        $api->put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
        $api->delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.updatdestroye');

        // 添加购物车
        $api->get('carts', 'CartController@index')->name('carts.index');
        $api->post('carts', 'CartController@store')->name('carts.store');    // 添加购物车商品
        $api->delete('carts/{sku}', 'CartController@remove')->name('carts.remove');   // 删除购物车商品

        // 订单
        $api->get('orders', 'OrdersController@index')->name('cart.index');
        $api->get('orders/{order}', 'OrdersController@show')->name('cart.show');
        $api->post('orders', 'OrdersController@store')->name('cart.store'); //
        $api->post('orders/{order}/received', 'OrdersController@received')->name('orders.received'); // 确认收获
        $api->post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');// 退款申请
        //
        $api->get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
        $api->post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');
        $api->post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');// 退款

        // 优惠券的展示 code是优惠券的code
        $api->get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');
    });


});
