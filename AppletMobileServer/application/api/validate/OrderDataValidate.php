<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/14
 * Time: 16:10
 */

namespace app\api\validate;


use think\Validate;

class OrderDataValidate extends Validate
{
    protected $rule=[
        'user_id' => 'require|number',
        'goods_ids' => 'require',
        'type' => 'require|alpha|in:pt,skill',
        'address_id' => 'require|number',
        'goods_num' => 'require',
        'totalExpress' => 'require|number',
        'totalSprice' => 'require|number',
    ];

    protected $message = [
        'user_id.require' => '用户id为空',
        'user_id.number' => '用户id不是数字',
        'goods_ids.require' => '订单单商品ID为空',
        'type.require' => '订单单商品类型为空',
        'type.alpha' => '订单单商品类型不是字母串',
        'type.in' => '订单商品类型值不在范围内',
        'address_id.require' => '地址ID为空',
        'address_id.number' => '地址ID不是数字',
        'goods_num.require' => '订单商品数量为空',
        'totalExpress.require' => '总运费为空',
        'totalExpress.number' => '总运费不是数字',
        'totalSprice.require' => '总订单金额为空',
        'totalSprice.number' => '总订单金额不是数字',
    ];
}