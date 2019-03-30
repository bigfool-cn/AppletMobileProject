<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/7
 * Time: 16:52
 */

namespace app\api\validate;


use think\Validate;

class UserAddressValidate extends Validate
{
    /**
     * 用户地址验证器
     */
    protected $rule = [
        'user_id'  =>  'require',
        'addressee' => 'require|max:100',
        'mobile' => ['require','max'=>11,'regex'=>'/^1([38][0-9]|4[579]|5[0-3,5-9]|6[6]|7[0135678]|9[89])\d{8}$/'],
        'address' => 'require|max:100',
        'xx_address' => 'require|max:255',
        'address_index' => 'require',

    ];

    protected $message  =   [
        'user_id.require' => '未知用户',
        'addressee.require'  => '收件人未填写',
        'addressee.max' => '收件人字符长度过长',
        'mobile.require'  => '联系电话未填写',
        'mobile.max'  => '联系电话长度不对',
        'mobile.regex'  => '联系电话格式不对',
        'address.require'  => '所在地区未填写',
        'address.max' => '所在地区长度过长',
        'xx_addressee.require'  => '详细地址未填写',
        'xx_addressee.max' => '详细地址字符长度过长',
        'address_index.require'  => '详细地址未填写',
    ];
}