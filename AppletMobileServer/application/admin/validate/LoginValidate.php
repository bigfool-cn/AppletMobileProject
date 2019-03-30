<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/2
 * Time: 15:13
 */

namespace app\admin\validate;

use think\Validate;

class LoginValidate extends Validate
{
    /**
     * 登陆验证器
     */

    protected $rule = [
        'username'  =>  'require|max:25',
        'password' =>  'require|min:6|max:32',
    ];

    protected $message  =   [
        'username.require' => '用户名必须',
        'username.max'     => '用户名最多不能超过25个字符',
        'password.require'   => '密码必须',
        'password.min'  => '密码必须6-32个长度',
        'password.max'  => '密码必须6-32个长度',
    ];
}