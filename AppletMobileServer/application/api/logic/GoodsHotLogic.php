<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/2
 * Time: 16:40
 */

namespace app\api\logic;


use app\helps\Tools;
use think\Db;

class GoodsHotLogic
{
    /**
     * 热销商品列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function index()
    {
        $goodsHots = Db::table('goods_hot')->alias('a')
            ->leftJoin('goods b','a.goods_id=b.goods_id')
            ->field('a.goods_id,b.goods_name,b.goods_desc,b.goods_image_urls,b.goods_sprice')
            ->order('a.sort DESC')
            ->select();
        foreach ($goodsHots as $key=>$goodsHot){
            $image_urls = explode(',',$goodsHot['goods_image_urls']);
            $goodsHots[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
        }
        return $goodsHots;
    }
}