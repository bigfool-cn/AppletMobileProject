<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 16:36
 */

namespace app\admin\validate;


use think\Validate;

class SlideShowValidate extends Validate
{
    /**
     * 首页轮播图验证器
     */
    protected $rule = [
        'image_url'  =>  'require',
        'goods_id' => 'require|min:0',
    ];

    protected $message  =   [
        'image_url.require' => '请选择图片',
        'goods_id.require'  => '请选择商品',
    ];
}