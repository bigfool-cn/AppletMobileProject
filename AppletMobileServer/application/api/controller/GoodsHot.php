<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/2
 * Time: 16:40
 */

namespace app\api\controller;


use app\api\logic\GoodsHotLogic;
use app\helps\Tools;
use think\Controller;

class GoodsHot extends Controller
{
    private $goodsHostLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsHostLogic = new GoodsHotLogic();
    }

    /**
     * 热销商品列表
     */
    public function index()
    {
        $goodsHots = $this->goodsHostLogic->index();
        Tools::returnJson(200,'ok',$goodsHots);
    }
}