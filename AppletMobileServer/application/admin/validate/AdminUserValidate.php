<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/20
 * Time: 15:51
 */

namespace app\admin\validate;


use think\Validate;

class AdminUserValidate extends Validate
{
    protected $rule = [
        'admin_user_name'=>'require|unique:admin_user',
        'admin_user_pwd'=>'require|min:6|confirm:confirm',
    ];

    protected $message = [
        'admin_user_name.reqire' => '用户名为空',
        'admin_user_name.unique' => '用户名已存在',
        'admin_user_pwd.require' => '密码为空',
        'admin_user_pws.confirm' => '两次输入的密码不一致',
    ];
}