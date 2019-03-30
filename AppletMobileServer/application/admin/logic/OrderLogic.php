<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/19
 * Time: 16:33
 */

namespace app\admin\logic;


use think\Db;

class OrderLogic
{
    public function index($user_name,$order_sn,$start,$end)
    {
        $query = Db::table('order')->alias('a');
        if($user_name){
            $user_ids = Db::table('user')->where('user_name','like','%'.$user_name.'%')->value('user_id');
            $query = $query->where(['a.user_id'=>$user_ids]);
        }
        $order_sn && $query->where(['order_sn'=>$order_sn]);
        $start && $query->where('a.create_time','>=',$start);
        $end && $query->where('a.create_time','<=', $end);
        $orders = $query->leftJoin('user b','a.user_id=b.user_id')
            ->field('a.order_sn,a.goods_id,a.goods_num,a.order_amount,a.order_express,a.parent_id,a.create_time,
                b.user_name,c.address,c.xx_address,c.mobile')
            ->leftJoin('user_address c','a.address_id=c.address_id')
            ->order('a.create_time DESC')->paginate(15);
        $ordersAll = $orders->all();
        foreach ($ordersAll as $key=>$value){
            if($value['parent_id'] != 0){
                $goods_name = Db::table('goods')->where(['goods_id'=>$value['goods_id']])->value('goods_name');
                $ordersAll[$key]['goods_id'] = $goods_name;
            }else{
                $goods_ids = explode(',',$value['goods_id']);
                if(count($goods_ids) === 1){
                    $goods_name = Db::table('goods')->where(['goods_id'=>$value['goods_id']])->value('goods_name');
                    $ordersAll[$key]['goods_id'] = $goods_name;
                }
                $goods_num = json_decode($value['goods_num'],true);
                $goods_num = array_sum(array_values($goods_num));
                $ordersAll[$key]['goods_num'] = $goods_num;
            }
        }
        return ['ordersAll'=>$ordersAll,'pages'=>$orders];
    }
}