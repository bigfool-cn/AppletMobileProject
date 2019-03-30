<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/27
 * Time: 11:31
 */

namespace app\api\controller;


use app\api\logic\GoodsLogic;
use app\helps\Redis;
use app\helps\RedisHelper;
use app\helps\Tools;
use think\Controller;

class Goods extends Controller
{
    private $goodsLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsLogic = new GoodsLogic();
    }

    public function index($id=0,$kw='')
    {
        $res = $this->goodsLogic->index($id,$kw);
        $data = array(
            'goods' => $res['goodsAll'],
            'pages' => $res['pages']
        );
        Tools::returnJson(200,'ok',$data);
    }

    public function detail($id)
    {
        !$id && Tools::returnJson(401,'参数错误');
        $res = $this->goodsLogic->detail($id);
        Tools::returnJson(200,'ok',array('goods'=>$res));
    }

    public function search($id=0,$kw='')
    {
        $res = $this->goodsLogic->search($id,$kw);
        Tools::returnJson(200,'ok',array('goods'=>$res['goodsAll'],'pages'=>$res['pages']));
    }
}