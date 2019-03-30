<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 16:36
 */

namespace app\admin\validate;


use think\Validate;

class GoodsHotValidate extends Validate
{
    /**
     * 热销商品验证器
     */
    protected $rule = [
        'goods_id' => 'require|min:0',
    ];

    protected $message  =   [
        'goods_id.require'  => '请选择商品',
    ];
}