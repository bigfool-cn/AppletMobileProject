<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/12
 * Time: 14:29
 */

namespace app\api\logic;


use app\helps\RedisHelper;
use app\helps\Tools;
use think\Db;

class GoodsSkillLogic
{
    /**
     * 抢购商品列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function index()
    {
        $goodsSkills = Db::table('goods_skill')->where(['is_end'=>0])
            ->field('goods_skill_id,goods_skill_name,goods_skill_desc,goods_skill_price,
                goods_skill_sprice,goods_skill_time,goods_skill_image_urls,is_end')
            ->select();
        foreach ($goodsSkills as $key=>$goodsSkill){
            $image_urls = explode(',',$goodsSkill['goods_skill_image_urls']);
            $goodsSkills[$key]['goods_skill_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
            unset($goodsSkills[$key]['goods_skill_image_urls']);
        }
        return $goodsSkills;
    }

    /**
     * 抢购商品详情
     * @param $goods_skill_id
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function detail($goods_skill_id)
    {
        $goodsSkill = Db::table('goods_skill')->where(['goods_skill_id'=>$goods_skill_id])
            ->field('goods_skill_id,goods_skill_name,goods_skill_desc,goods_skill_price,
                goods_skill_sprice,goods_skill_time,goods_skill_image_urls,is_end,goods_skill_express,
                goods_skill_stock,goods_skill_detail,goods_skill_param')
            ->find();
        $goods_skill_image_urls = explode(',',$goodsSkill['goods_skill_image_urls']);
        foreach ($goods_skill_image_urls as $key=>$goods_skill_image_url){
            $goods_skill_image_urls[$key] = Tools::cutImg2CdnImg($goods_skill_image_url,320);
        }
        $goodsSkill['goods_skill_image_urls'] = $goods_skill_image_urls;
        $redis = new RedisHelper();
        $count = $redis->llen('goods_skill_'.$goods_skill_id);
        $goodsSkill['count'] = $count;
        return $goodsSkill;
    }
}