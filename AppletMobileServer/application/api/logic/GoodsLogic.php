<?php
/**
 * Created by PhpStorm.
 * User=> JS_chen
 * Date=> 2019/2/27
 * Time=> 11=>31
 */

namespace app\api\logic;
require_once '/home/ubuntu/xunsearch/sdk/php/lib/XS.php';

use app\helps\Swcs;
use app\helps\Tools;
use think\Db;



class GoodsLogic
{
    /**
     * 商品列表
     * @return array
     */
    public function index($id)
    {
        $query =  Db::table('goods');
        $id && $query = $query->where(['goods_cate_id'=>$id]);
        $goods = $query->field('goods_id,goods_name,goods_desc,goods_price,goods_sprice,goods_image_urls')
            ->paginate('8');
        $goodsAll = $goods->all();
        foreach ($goodsAll as $key=>$value){
            $image_urls = explode(',',$value['goods_image_urls']);
            $goodsAll[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
            unset($goodsAll[$key]['goods_image_urls']);
            $count = Db::table('order')->where(['goods_id'=>$value['goods_id']])->where('parent_id != 0')->count();
            $goodsAll[$key]['count'] = $count;
        }
        return ['goodsAll'=>$goodsAll,'pages'=>$goods];
    }

    public function detail($id)
    {
        $goods = Db::table('goods')->alias('a')
            ->where(['a.goods_id'=>$id])
            ->leftJoin('goods_detail_param b','a.goods_id=b.goods_id')
            ->field('a.goods_id,a.goods_name,a.goods_desc,a.goods_image_urls,a.goods_price,a.goods_sprice,
                a.goods_express,a.goods_stock,b.goods_detail,b.goods_param')
            ->find();
        $goods_image_urls = explode(',',$goods['goods_image_urls']);
        foreach ($goods_image_urls as $key=>$goods_image_url){
            $goods_image_urls[$key] = Tools::cutImg2CdnImg($goods_image_url,320);
        }
        $goods['goods_image_urls'] = $goods_image_urls;
        return $goods;
    }

    /**
     * 搜索
     * @param $id goods_cate_id
     * @param $kw 搜索值
     * @return array
     */
    public function search($id,$kw)
    {
        if($kw){
            // 获取搜索值分词
            $words = Swcs::getWords($kw);
            $goodsIds = array();
            foreach ($words as $key=>$value){
                // 查在商品分词库找搜索分词符合的商品id
                $goods_ids = Db::table('goods_words')->where("FIND_IN_SET('$value',goods_words)")
                    ->column('goods_id');
                $goodsIds = array_merge($goodsIds,$goods_ids);
            }
            $query = Db::table('goods')->where(['goods_id'=>$goodsIds])
                ->field('goods_id,goods_name,goods_desc,goods_price,goods_sprice,goods_image_urls')
                ->paginate('8');
            $id && $query ->where(['goods_cate_id'=>$id]);
            $goodsAll = $query->all();
            foreach ($goodsAll as $key=>$value){
                $image_urls = explode(',',$value['goods_image_urls']);
                $goodsAll[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
                unset($goodsAll[$key]['goods_image_urls']);
                $count = Db::table('order')->where(['goods_id'=>$value['goods_id']])->count();
                $goodsAll[$key]['count'] = $count;
            }
            return ['goodsAll'=>$goodsAll,'pages'=>$query];
        }else{
            $query = Db::table('goods')
                ->field('goods_id,goods_name,goods_desc,goods_price,goods_sprice,goods_image_urls')
                ->paginate('8');
            $id && $query ->where(['goods_cate_id'=>$id]);
            $goodsAll = $query->all();
            foreach ($goodsAll as $key=>$value){
                $image_urls = explode(',',$value['goods_image_urls']);
                $goodsAll[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
                unset($goodsAll[$key]['goods_image_urls']);
                $count = Db::table('order')->where(['goods_id'=>$value['goods_id']])->count();
                $goodsAll[$key]['count'] = $count;
            }
            return ['goodsAll'=>$goodsAll,'pages'=>$query];
        }
    }
}
