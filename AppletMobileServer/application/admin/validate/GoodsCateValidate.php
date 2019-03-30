<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 16:36
 */

namespace app\admin\validate;


use think\Validate;

class GoodsCateValidate extends Validate
{
    /**
     * 商品分类验证器
     */
    protected $rule = [
        'parent_id'  =>  'require',
        'goods_cate_name' => 'require|max:50',
    ];

    protected $message  =   [
        'parent_id.require' => '请选择根分类',
        'goods_cate_name.require'  => '请输入商品分类名称',
        'goods_cate_name.max'  => '商品分类名称长度不能超过50',
    ];
}