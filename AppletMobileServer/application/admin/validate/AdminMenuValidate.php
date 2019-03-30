<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 16:36
 */

namespace app\admin\validate;


use think\Validate;

class AdminMenuValidate extends Validate
{
    /**
     * 后台栏目验证器
     */
    protected $rule = [
        'parent_id'  =>  'require',
        'admin_menu_name' => 'require|max:50',
    ];

    protected $message  =   [
        'parent_id.require' => '请选择根目录',
        'admin_menu_name.require'  => '请输入栏目名称',
        'admin_menu_name.max'  => '栏目名称长度不能超过50',
    ];
}