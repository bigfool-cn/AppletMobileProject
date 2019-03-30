<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
Route::group('/',function (){
    Route::get('$','admin/index/index');
    Route::get('index$','admin/index/index');
    Route::get('welcome$','admin/index/welcome');
    Route::get('logout$','admin/login/logout');
})->middleware(['login']);

Route::group('/',function (){
    // 上传图片到腾讯云cos
    Route::post('uploadimage','admin/upload_file/uploadImage');
    // 上传ueditor图片到腾讯云cos
    Route::post('ueditoruploadimage','admin/upload_file/ueditorUploadImage');
    // 删除图片
    Route::post('delimage','admin/upload_file/delImage');

    //会员
    Route::group('user',function (){
        Route::get('$','admin/user/index');
        Route::get('/index','admin/user/index');
        Route::get('/stat','admin/user/stat');
    });
    //会员登录日志
    Route::group('userloginlog',function (){
        Route::get('$','admin/UserLoginLog/index');
        Route::get('/index','admin/UserLoginLog/index');
        Route::get('delete','admin/UserLoginLog/delete');
    });
    //管理员
    Route::group('adminuser',function (){
        Route::get('$','admin/admin_user/index');
        Route::get('/index','admin/admin_user/index');
        Route::rule('/add','admin/admin_user/add','GET|POST');
        Route::rule('/update','admin/admin_user/update','GET|POST');
        Route::get('/loginlog','admin/admin_user/loginLog');
    });

    //后台栏目
    Route::group('adminmenu',function (){
        Route::get('$','admin/admin_menu/index');
        Route::get('/index','admin/admin_menu/index');
        Route::rule('/add','admin/admin_menu/add','GET|POST');
        Route::rule('/update','admin/admin_menu/update','GET|POST');
        Route::get('/delete','admin/admin_menu/delete');
        Route::get('/sort','admin/admin_menu/sort');
    });

    //小程序栏目
    Route::group('appletmenu',function (){
        Route::get('$','admin/applet_menu/index');
        Route::get('/index','admin/applet_menu/index');
        Route::rule('/add','admin/applet_menu/add','GET|POST');
        Route::rule('/update','admin/applet_menu/update','GET|POST');
        Route::get('/delete','admin/applet_menu/delete');
        Route::get('/sort','admin/applet_menu/sort');
        Route::get('/open','admin/applet_menu/open');
    });

    //商品分类
    Route::group('goodscate',function (){
        Route::get('$','admin/goods_cate/index');
        Route::get('/index','admin/goods_cate/index');
        Route::rule('/add','admin/goods_cate/add','GET|POST');
        Route::rule('/update','admin/goods_cate/update','GET|POST');
        Route::get('/delete','admin/goods_cate/delete');
        Route::get('/sort','admin/goods_cate/sort');
    });

    //商品
    Route::group('goods',function (){
        Route::get('$','admin/goods/index');
        Route::get('/index','admin/goods/index');
        Route::rule('/add','admin/goods/add','GET|POST');
        Route::rule('/update','admin/goods/update','GET|POST');
        Route::get('/delete','admin/goods/delete');
        Route::post('/goodslist','admin/goods/goodsList');
    });

    //秒杀商品
    Route::group('goodsskill',function (){
        Route::get('$','admin/goods_skill/index');
        Route::get('/index','admin/goods_skill/index');
        Route::rule('/add','admin/goods_skill/add','GET|POST');
        Route::rule('/update','admin/goods_skill/update','GET|POST');
        Route::get('/delete','admin/goods_skill/delete');
    });

    //商城首页轮播图
    Route::group('slideshow',function (){
        Route::get('$','admin/slide_show/index');
        Route::get('/index','admin/slide_show/index');
        Route::rule('/add','admin/slide_show/add','GET|POST');
        Route::rule('/update','admin/slide_show/update','GET|POST');
        Route::get('/delete','admin/slide_show/delete');
        Route::get('/sort','admin/slide_show/sort');
    });

    //热销商品
    Route::group('goodshot',function (){
        Route::get('$','admin/goods_hot/index');
        Route::get('/index','admin/goods_hot/index');
        Route::rule('/add','admin/goods_hot/add','GET|POST');
        Route::rule('/update','admin/goods_hot/update','GET|POST');
        Route::get('/delete','admin/goods_hot/delete');
        Route::get('/sort','admin/goods_hot/sort');
    });

    //订单
    Route::group('order',function (){
        Route::get('$','admin/order/index');
        Route::get('/index','admin/order/index');
    });

    // 权限列表
    Route::group('permission',function (){
        Route::get('$','admin/permission/index');
        Route::get('/index','admin/permission/index');
        Route::rule('/add','admin/permission/add','GET|POST');
        Route::get('/delete','admin/permission/delete');
    });

    // 角色列表
    Route::group('role',function (){
        Route::get('$','admin/role/index');
        Route::get('/index','admin/role/index');
        Route::rule('/add','admin/role/add','GET|POST');
        Route::rule('/update','admin/role/update','GET|POST');
        Route::get('/delete','admin/role/delete');
    });

    // 用户角色列表
    Route::group('userrole',function (){
        Route::get('$','admin/user_role/index');
        Route::get('/index','admin/user_role/index');
        Route::rule('/add','admin/user_role/add','GET|POST');
        Route::rule('/update','admin/user_role/update','GET|POST');
        Route::get('/delete','admin/user_role/delete');
    });
})->middleware(['login','permission']);
Route::get('/not-auth','admin/Auth/notAuth');
Route::rule('/login$', 'admin/login/login','GET|POST');
Route::post('/saveipconfig$','admin/login/saveIpConfig');
