<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/18
 * Time: 14:26
 */

namespace app\admin\logic;

use app\helps\Tools;
use think\Db;

class IndexLogic
{

    private $last_week;//一周前
    public function __construct()
    {
       $this->last_week = strtotime(date('Y-m-d 00:00:00',strtotime('-7day')));
    }

    public function index()
    {
        $admin_menus = Db::table('admin_menu')
            ->field('admin_menu_id,admin_menu_name,controller,action,parent_id')
            ->order(['sort'=>'desc','parent_id'=>'asc'])->all();
        $menus = array();
        foreach($admin_menus as $key=>$value) {
            $value['controller'] && $value['url'] = '/'.strtolower($value['controller']).'/'.strtolower($value['action']);
            if($value['parent_id'] == 0){
                $menus[$value['admin_menu_id']] = $value;
                $menus[$value['admin_menu_id']]['child'] = isset($menus[$value['admin_menu_id']]['child'])?array($menus[$value['admin_menu_id']]['child']):array();
            }else {
                $menus[$value['parent_id']]['child'][] = $value;
            }
        }
        $admin_menus = array_values($menus);
        return $admin_menus;
    }

    /**
     * 后台首页
     * @return array
     */
    public function welcome()
    {
        $dateArr = Tools::getNDate(15);
        $user_count = array();
        $order_count = array();
        foreach ($dateArr as $key=>$value){
            $userCount = Db::table('user_login_log')->where("FROM_UNIXTIME(`login_time`,'%Y-%m-%d')='$value'")
                ->group('user_id')->count();
            array_push($user_count,$userCount);
            $orderCount = Db::table('order')->where(['parent_id'=>0])
                ->where("FROM_UNIXTIME(`create_time`,'%Y-%m-%d')='$value'")->count();
            array_push($order_count,$orderCount);
        }
        $users = ['users_count'=>json_encode($user_count),'users_day'=>json_encode($dateArr)];
        $orders = ['orders_count'=>json_encode($order_count),'orders_day'=>json_encode($dateArr)];
        //当日活跃用户数
        $today_users = Db::table('user')->where('login_time','>',strtotime(date("Y-m-d"),time()))->count();
        //会员总数
        $total_users = Db::table('user')->count();

        // 当日订单
        $today_orders = Db::table('order')->where(['parent_id'=>0])
            ->where('create_time','>',strtotime(date('Y-m-d')))->count();
        // 总订单
        $total_orders = Db::table('order')->where(['parent_id'=>0])->count();
        $data = [
            'users' => $users,
            'orders' => $orders,
            'today_users' => $today_users,
            'total_users' => $total_users,
            'today_orders' => $today_orders,
            'total_orders' => $total_orders
        ];
        return $data;
    }

}