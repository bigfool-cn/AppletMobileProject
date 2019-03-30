<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/29
 * Time: 14:00
 */

namespace app\admin\model;


use think\Model;

class UserModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'user';
    //设置主键
    protected $pk = 'user_id';

    //格式化性别
    public function getUserGenderAttr($value)
    {
        $status = [0=>'未知',1=>'男',2=>'女'];
        return $status[$value];
    }

}