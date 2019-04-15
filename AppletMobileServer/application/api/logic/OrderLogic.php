<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/6
 * Time: 16:59
 */

namespace app\api\logic;


use app\api\validate\OrderDataValidate;
use app\helps\RedisHelper;
use app\helps\SnowFlake;
use app\helps\Tools;
use think\Db;


class OrderLogic
{

    private $orderDataValidate;
    public function __construct()
    {
        $this->orderDataValidate = new OrderDataValidate();
    }

    /**
     * 检查库存
     * @param $id goods_id
     * @param $num 购买数量
     * @return array
     */
    public function checkStock($id,$num)
    {
        $goods_stock = Db::table('goods')->where(['goods_id'=>$id])->value('goods_stock');
        if($goods_stock < $num){
            return ['code'=>2002,'msg'=>'库存不足'];
        }else{
            return ['code'=>200,'msg'=>'ok'];
        }

    }

    /**
     * 秒杀
     * @param $id 秒杀商品ID
     * @param $user_id 用户ID
     * @return array
     */
    public function skill($id,$user_id)
    {
        $redis = new RedisHelper();
        // 必须先拼接key,切勿在函数的参数位置拼接，否则则会出现超卖
        $pp_key = 'goods_skill_pp_'.$id;
        if($redis->sismember($pp_key,$user_id)){
            return ['code'=>2003,'msg'=>'抢购资格已用完!'];
        }
        $key = 'goods_skill_'.$id;
        $len = $redis->rpop($key);
        if(!$len){
            $redis->sadd($pp_key,$user_id);
            return ['code'=>200,'msg'=>'抢购成功'];
        }else{
            return ['code'=>2002,'msg'=>'秒杀已结束'];
        }
    }

    /**
     * 结算商品信息
     * @param $ids 商品id
     * @param $type 商品类型
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function orderGoods($ids,$type)
    {
        $idsArr = explode(',',$ids);
        if($type == 'skill'){
            // 秒杀商品
            $goodsSkills = Db::table('goods_skill')->where(['goods_skill_id'=>$idsArr])
                ->field('goods_skill_id as goods_id,goods_skill_name as goods_name,goods_skill_desc as goods_desc,
                    goods_skill_image_urls as goods_image_urls,goods_skill_sprice as goods_sprice,
                    goods_skill_express as goods_express,goods_skill_stock as goods_stock')
                ->select();
            foreach ($goodsSkills as $key=>$value){
                $image_urls = explode(',',$value['goods_image_urls']);
                $goodsSkills[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
                unset($goodsSkills[$key]['goods_image_urls']);
                $count = Db::table('order')->where(['goods_id'=>$value['goods_id']])->count();
                $goodsSkills[$key]['count'] = $count;
            }
            return $goodsSkills;
        }else{
            // 普通商品
            $goods = Db::table('goods')->where(['goods_id'=>$idsArr])
                ->field('goods_id,goods_name,goods_desc,goods_image_urls,goods_sprice,goods_express,goods_stock')
                ->select();
            foreach ($goods as $key=>$value){
                $image_urls = explode(',',$value['goods_image_urls']);
                $goods[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
                unset($goods[$key]['goods_image_urls']);
                $count = Db::table('order')->where(['goods_id'=>$value['goods_id']])->count();
                $goods[$key]['count'] = $count;
            }
            return $goods;
        }
    }

    /**
     * 提交订单
     * @param $form
     * @return array
     */
    public function submitOrder($form)
    {
        if($this->orderDataValidate->check($form)){
            $goods_ids = explode(',',$form['goods_ids']);
            $goods_num = json_decode($form['goods_num'],true);
            // 实际运费
            $totalExpress = 0;
            // 实际订单价格
            $totalSprice = 0;
            // 获取商品实际价格
            if($form['type']=='pt'){
                $goods = Db::table('goods')->where(['goods_id'=>$goods_ids])
                    ->field('goods_id,goods_sprice,goods_express')->select();
                foreach ($goods as $key=>$value){
                    $totalExpress += $value['goods_express'];
                    $totalSprice = $totalSprice + $value['goods_sprice'] * $goods_num[$value['goods_id']];
                }
            }else if ($form['type']=='skill'){
                $redis = new RedisHelper();
                $pp_key = 'goods_skill_pp_'.$form['goods_ids'];
                $user_id = $form['user_id'];
                // 判断用户是否抢购成功用户集合
                if(!$redis->sismember($pp_key,$user_id)){
                    return ['code'=>2004,'msg'=>'未抢购成功','data'=>array()];
                }
                $goods = Db::table('goods_skill')->where(['goods_skill_id'=>$form['goods_ids']])
                    ->field('goods_skill_id,goods_skill_sprice,goods_skill_express')->select();
                foreach ($goods as $key=>$value){
                    $totalExpress += $value['goods_skill_express'];
                    $totalSprice = $totalSprice + $value['goods_skill_sprice'] * $goods_num[$value['goods_skill_id']];
                }
            }
            if(($totalExpress != $form['totalExpress']) && ($totalSprice != $form['totalSprice'])){
                return ['code'=>400,'msg'=>'提交订单失败','data'=>array()];
            }
            // 机器ID
            SnowFlake::machineId(1);
            // 生成订单号
            $order_sn = SnowFlake::createOnlyId();
            $data = array(
                'user_id' => $form['user_id'],
                'goods_id' => $form['goods_ids'],
                'goods_num' => $form['goods_num'],
                'address_id' => $form['address_id'],
                'order_sn' => $order_sn,
                'order_express' => $totalExpress,
                'order_amount' => $totalSprice,
                'order_type' => $form['type'],
                'user_msg' => $form['user_msg'],
                'create_time' => time()
            );
            if ($form['type'] == 'pt') {
                try {
                    Db::startTrans();
                    $order_id = Db::table('order')->insertGetId($data);
                    // 多个商品时，拆分订单，以商品ID拆分
                    if (count($goods_ids) > 1){
                        foreach ($goods_ids as $key => $goods_id) {
                            $goods = Db::table('goods')->where(['goods_id' => $goods_id])
                                ->field('goods_id,goods_sprice,goods_express,goods_stock')->find();
                            if($goods_num[$goods_id] > $goods['goods_stock']){
                                Db::rollback();
                                return ['code'=>400,'msg'=>'提交订单失败，商品库存不足，请重新选择!'];
                            }
                            // 机器ID
                            SnowFlake::machineId(1);
                            // 生成订单号
                            $order_sn = SnowFlake::createOnlyId();
                            $data = array(
                                'user_id' => $form['user_id'],
                                'goods_id' => $goods_id,
                                'goods_num' => $goods_num[$goods_id],
                                'address_id' => $form['address_id'],
                                'order_sn' => $order_sn,
                                'order_express' => $goods['goods_express'],
                                'order_amount' => $goods['goods_sprice'] * $goods_num[$goods_id],
                                'order_type' => $form['type'],
                                'user_msg' => $form['user_msg'],
                                'parent_id' => $order_id,
                                'create_time' => time()
                            );
                            Db::table('order')->insert($data);
                            // 更新库存
                            Db::table('goods')->where(['goods_id'=>$goods_id])
                                ->dec('goods_stock',$goods_num[$goods_id])->update();
                        }
                    }else{
                        // 更新库存
                        Db::table('goods')->where(['goods_id'=>$goods_ids])
                            ->dec('goods_stock',$goods_num[$form['goods_ids']])->update();
                    }
                    Db::commit();
                    return ['code'=>200,'msg'=>'提交订单成功','data'=>array('order_id'=>$order_id)];
                }catch (\Exception $e){
                    Db::rollback();
                    return ['code'=>500,'msg'=>$e->getMessage(),'data'=>array()];
                }
            }else{
                try{
                    Db::startTrans();
                    $order_id = Db::table('order')->insertGetId($data);
                    // 更新库存
                    Db::table('goods_skill')->where(['goods_skill_id'=>$form['goods_ids']])
                        ->dec('goods_skill_stock',1)->update();
                    Db::commit();
                    return ['code'=>200,'msg'=>'提交订单成功','data'=>array('order_id'=>$order_id)];
                }catch (\Exception $e){
                    return ['code'=>500,'msg'=>'提交订单失败','data'=>array()];
                }
            }
        }else{
            //var_dump($this->orderDataValidate->getError());die;
            return ['code'=>400,'msg'=>'提交订单失败','data'=>array()];
        }
    }

    /**
     * 订单列表
     * @param $page 页数
     * @param $user_id 用户id
     * @return array
     */
    public function index($user_id)
    {
        $orders = Db::table('order')->where(['user_id'=>$user_id,'parent_id'=>0])
            ->paginate(4);
        $ordersAll = $orders->all();
        foreach ($ordersAll as $key=>$order){
            $goodsArr = json_decode($order['goods_num'],true);
            $ordersAll[$key]['count'] = array_sum(array_values($goodsArr));
            $data = $this->getGoods($goodsArr,$user_id,$order['order_id'],$order['order_amount']);
            $ordersAll[$key]['order_total_amount'] = sprintf("%.2f",$order['order_amount'] + $order['order_express']);
            $ordersAll[$key]['goods'] = $data;
        }
        return ['ordersAll'=>$ordersAll,'page'=>$orders];
    }

    /**
     * 订单详情
     * @param $order_id 订单id
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function detail($order_id)
    {
        $order = Db::table('order')->alias('a')
            ->leftJoin('user_address b','a.address_id=b.address_id')
            ->where(['a.order_id'=>$order_id])
            ->field('order_id,goods_id,goods_num,a.user_id,order_sn,order_express,order_amount,
                order_status,user_msg,FROM_UNIXTIME(`a`.`create_time`,"%Y-%m-%d %H:%i:%s") as create_time,addressee,mobile,address,xx_address')
            ->find();
        $goodsArr = json_decode($order['goods_num'],true);
        // 获取商品数量
        $order['count'] = array_sum(array_values($goodsArr));
        $data = $this->getGoods($goodsArr,$order['user_id'],$order['order_id'],$order['order_amount']);
        // 订单总金额
        $order['order_total_amount'] = sprintf("%.2f",$order['order_amount'] + $order['order_express']);
        // 订单商品信息
        $order['goods'] = $data;
        return $order;
    }

    /**
     * 获取订单商品信息
     * @param $goodsArr 订单号与数量数组
     * @param $user_id 用户id
     * @param $order_id 订单id
     * @param $order_amount 订单商品实际金额
     * @return array
     */
    private function getGoods($goodsArr,$user_id,$order_id,$order_amount)
    {
        $data = array();
        foreach ($goodsArr as $k=>$v){
            $goods = Db::table('goods')->where(['goods_id'=>$k])->field('goods_desc,goods_image_urls')->find();
            $image_urls = explode(',',$goods['goods_image_urls']);
            $goods['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
            unset($goods['goods_image_urls']);
            $amount = Db::table('order')->where(['user_id'=>$user_id,'goods_id'=>$k,'parent_id'=>$order_id])
                ->value('order_amount');

            $goods['goods_sprice'] = count($goodsArr)==1 ? $order_amount / $v : $amount / $v;
            $goods['count'] = $goodsArr[$k];
            $data[] = $goods;
        }
        return $data;
    }

    /**
     * 取消订单
     * @param $order_id
     * @param $user_id
     * @return array
     */
    public function delOrder($order_id,$user_id)
    {
        try{
            Db::startTrans();
            $goods_num = Db::table('order')->where(['order_id'=>$order_id])->value('goods_num');
            $goods_num = json_decode($goods_num,true);
            foreach ($goods_num as $key=>$value){
                Db::table('goods')->where(['goods_id'=>$key])->inc('goods_stock',$value)->update();
            }
            Db::table('order')->where(['order_id'=>$order_id])->whereOr(['parent_id'=>$order_id])->delete();
            $data = $this->index($user_id);
            Db::commit();
            return ['code'=>200,'msg'=>'取消订单成功','data'=>$data];
        }catch (\Exception $e){
            Db::rollback();
            return ['code'=>500,'msg'=>'取消订单失败','data'=>array()];
        }
    }

}
