<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/26
 * Time: 18:19
 */

namespace app\api\controller;


use app\helps\Tools;
use think\Db;

class SlideShow
{
    public function index()
    {
        $slideShows = Db::table('slide_show')->order('sort desc')
            ->limit(4)->field('image_url,goods_id')->select();
        foreach ($slideShows as $key=>$value){
            $slideShows[$key]['image_url_320'] = Tools::cutImg2CdnImg($value['image_url'],320);
        }

        Tools::returnJson(200,'ok',$slideShows);
    }
}