<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/6
 * Time: 16:59
 */

namespace app\api\controller;


use app\api\logic\OrderLogic;
use app\helps\Tools;
use think\Controller;

class Order extends  Controller
{
    private $orderLogic;
    public function __construct()
    {
        parent::__construct();
        $this->orderLogic = new OrderLogic();
    }

    /**
     * 检查商品库存
     * @param int $id goods_id
     * @param int $num 数量
     */
    public function checkStock($id=0,$num=0)
    {
        (!$id || !$num) && Tools::returnJson(400,'参数错误');
        $res = $this->orderLogic->checkStock($id,$num);
        Tools::returnJson($res['code'],$res['msg']);
    }

    /**
     * 秒杀商品
     * @param int $id 秒杀商品id
     * @param int $user_id 用户id
     */
    public function skill($id=0,$user_id=0)
    {
        (!$id || !$user_id) && Tools::returnJson(400,'参数错误');
        $res = $this->orderLogic->skill($id,$user_id);
        Tools::returnJson($res['code'],$res['msg']);
    }

    /**
     * 结算商品信息
     */
    public function orderGoods()
    {
        $ids = $this->request->post('ids');
        $type = $this->request->post('type');
        (!$ids || !$type) && Tools::returnJson(400,'参数错误');
        $res = $this->orderLogic->orderGoods($ids,$type);
        Tools::returnJson(200,'ok',$res);
    }

    /**
     * 提交订单
     */
    public function submitOrder()
    {
        $form = $this->request->post();
        !$form && Tools::returnJson(400,'提交数据为空');
        $res = $this->orderLogic->submitOrder($form);
        Tools::returnJson($res['code'],$res['msg'],$res['data']);
    }

    /*
     * 订单列表
     */
    public function index($page=0,$user_id=0)
    {
        !$user_id && Tools::returnJson(400,'参数错误');
        $res = $this->orderLogic->index($user_id);
        Tools::returnJson(200,'ok',$res);
    }

    /**
     * 订单详情
     * @param $order_id 订单id
     */
    public function detail($order_id)
    {
        !$order_id && Tools::returnJson(400,'参数错误');
        $res = $this->orderLogic->detail($order_id);
        Tools::returnJson(200,'ok',$res);
    }

    /**
     * 取消订单
     * @param int $order_id
     * @param int $user_id
     */
    public function delOrder($order_id=0,$user_id=0)
    {
        !($order_id && $user_id) && Tools::returnJson(400,'参数错误');
        $res = $this->orderLogic->delOrder($order_id,$user_id);
        Tools::returnJson($res['code'],$res['msg'],$res['data']);
    }
}