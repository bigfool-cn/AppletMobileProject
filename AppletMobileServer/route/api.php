<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/23
 * Time: 10:51
 */

//api接口路由


Route::group('/api',function (){
    // 获取token
    Route::post('/wxlogin','api/WxLogin/wxLogin');
    Route::get('/check-token','api/WxLogin/checkToken');
    Route::group('',function (){
        // 轮播图列表
        Route::get('/slide-show/list','api/SlideShow/index');
        // 商品列表
        Route::get('/goods/list','api/Goods/index');
        // 搜索商品
        Route::get('/goods/search','api/Goods/search');
        // 商品详情
        Route::get('/goods/detail','api/Goods/detail');
        // 商品分类
        Route::get('/goods-cate/list','api/GoodsCate/index');
        // 秒杀商品
        Route::get('/goods-skill/list','api/GoodsSkill/index');
        // 秒杀商品详情
        Route::get('/goods-skill/detail','api/GoodsSkill/detail');
        // 热销商品
        Route::get('/goods-hot/list','api/GoodsHot/index');
        // 检查商品库存
        Route::get('/order/check-stock','api/Order/checkStock');
        // 秒杀商品
        Route::get('/order/skill','api/Order/skill');
        // 结算商品信息
        Route::post('/order/order-goods','api/Order/orderGoods');
        // 提交订单
        Route::post('/order/submit-order','api/Order/submitOrder');
        // 订单列表
        Route::get('/order/order-list','api/Order/index');
        // 订单详情
        Route::get('/order/order-detail','api/Order/detail');
        // 取消订单
        Route::get('/order/del-order','api/Order/delOrder');
        // 收货地址列表
        Route::get('/user-address/list','api/UserAddress/index');
        // 添加地址
        Route::post('/user-address/add','api/UserAddress/add');
        // 修改地址默认选项
        Route::post('/user-address/editmr','api/UserAddress/editMr');
        // 修改地址
        Route::rule('/user-address/edit','api/UserAddress/edit','GET|POST');
        // 删除收货地址
        Route::post('/user-address/delete','api/UserAddress/delete');
        // 获取用户默认收货地址
        Route::get('/user-address/get-mr-address','api/UserAddress/getMrAddress');
    })->middleware(['token']);
});