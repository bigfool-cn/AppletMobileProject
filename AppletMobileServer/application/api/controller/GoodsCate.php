<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/1
 * Time: 9:41
 */

namespace app\api\controller;


use app\api\logic\GoodsCateLogic;
use app\helps\Tools;
use think\Controller;

class GoodsCate extends Controller
{
    private $goodsCateLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsCateLogic = new GoodsCateLogic();
    }

    /**
     * 商品分类列表，每组五个分类
     */
    public function index()
    {
        $goodsCates = $this->goodsCateLogic->index();
        Tools::returnJson(200,'ok',$goodsCates);
    }
}