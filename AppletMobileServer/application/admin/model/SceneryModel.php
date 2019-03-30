<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/29
 * Time: 14:00
 */

namespace app\admin\model;


use think\Model;

class SceneryModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'scenery';
    //设置主键
    protected $pk = 'scenery_id';

    //格式化发布状态
    public function getSceneryStateAttr($value)
    {
        $status = [0=>'未发布',1=>'已发布'];
        return $status[$value];
    }

    //将转移的标签转义回来
    public function getSceneryContentAttr($value)
    {
        $scenery_conetnt = htmlspecialchars_decode($value);
        return $scenery_conetnt;
    }

}