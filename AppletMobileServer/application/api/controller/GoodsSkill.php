<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/12
 * Time: 14:29
 */

namespace app\api\controller;


use app\api\logic\GoodsSkillLogic;
use app\helps\Tools;
use think\Controller;

class GoodsSkill extends Controller
{
    private $goodsSkillLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsSkillLogic = new GoodsSkillLogic();
    }

    /**
     * 秒杀商品列表
     */
    public function index()
    {
        $res = $this->goodsSkillLogic->index();
        Tools::returnJson(200,'ok',$res);
    }

    public function detail($id=0)
    {
        !$id && Tools::returnJson(401,'参数错误');
        $goodsSkill = $this->goodsSkillLogic->detail($id);
        Tools::returnJson(200,'ok',$goodsSkill);
    }
}