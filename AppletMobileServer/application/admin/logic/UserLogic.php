<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/29
 * Time: 11:54
 */

namespace app\admin\logic;

use app\admin\model\UserModel;

use think\Controller;
use think\Db;

class UserLogic extends Controller
{
    /**
     * 用户列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function index($start,$end)
    {
        if($start && $end)
        {
            $users = UserModel::where('login_time','between',"$start,$end")->order(['user_id'=>'desc'])->paginate('10');
        }elseif ($start){
            $users = UserModel::where('login_time','>',"$start")->order(['user_id'=>'desc'])->paginate('10');
        }elseif($end){
            $users = UserModel::where('login_time','<',"$end")->order(['user_id'=>'desc'])->paginate('10');
        }else{
            $users = UserModel::order(['user_id'=>'desc'])->paginate('10');
        }
        return ['users'=>$users->all(),'pages'=>$users];
    }

    /**
     * 用户一周内访问次数
     * @param $id 用户id
     */
    public function stat($id)
    {
        !$id && Tools::returnJson(404,'参数错误');
        $last_week =  strtotime(date('Y-m-d 00:00:00',strtotime('-7day')));
        $users = Db::table('user_login_log')->where('login_time','>',$last_week)
            ->field(['count(user_id) as count','FROM_UNIXTIME(login_time,"%m-%d") as day'])
            ->group('day')->select();
        $users_count = array_column($users,'count');
        $users_day = array_column($users,'day');
        $users = ['users_count'=>json_encode($users_count),'users_day'=>json_encode($users_day)];
        return $users;
    }
}