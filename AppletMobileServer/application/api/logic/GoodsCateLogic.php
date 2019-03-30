<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/1
 * Time: 9:41
 */

namespace app\api\logic;


use think\Db;

class GoodsCateLogic
{
    /**
     * 商品分类列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function index()
    {
        $goodsCates = Db::table('goods_cate')->order('sort DESC')
            ->field('goods_cate_id,goods_cate_name')->select();
        $data = array('goods_cate_id'=>0,'goods_cate_name'=>'全部');
        array_unshift($goodsCates,$data);
        $goodsCates = array_chunk($goodsCates,5);
        return $goodsCates;
    }
}