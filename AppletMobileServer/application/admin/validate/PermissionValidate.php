<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/20
 * Time: 10:18
 */

namespace app\admin\validate;


use think\Validate;

class PermissionValidate extends Validate
{
    protected $rule = [
        'permission_name' => 'require',
        'method' => 'require',
        'controller' => 'require',
        'action' => 'require'
    ];

    protected $message = [
        'permission_name.require' => '权限名称为空',
        'method.require' => '请求方法为空',
        'controller.require' => '控制器为空',
        'action.require' => '方法为空',
    ];
}