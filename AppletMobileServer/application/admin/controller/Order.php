<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/19
 * Time: 16:33
 */

namespace app\admin\controller;


use app\admin\logic\OrderLogic;
use think\Controller;

class Order extends Controller
{
    private $orderLogic;
    public function __construct()
    {
        parent::__construct();
        $this->orderLogic = new OrderLogic();
    }

    public function index($user_name='',$order_sn='',$start='',$end='')
    {
        $start_str = strtotime($start);
        $end_str = strtotime($end);
        $res = $this->orderLogic->index($user_name,$order_sn,$start_str,$end_str);
//        var_dump($res);die;
        $this->assign('user_name',$user_name);
        $this->assign('order_sn',$order_sn);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('orders',$res['ordersAll']);
        $this->assign('pages',$res['pages']);
        return $this->fetch();
    }
}