<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/17
 * Time: 11:11
 */

namespace app\admin\logic;


use app\helps\Tools;
use think\Db;

class UserLoginLogLogic
{
    /**
     * 会员登陆日志
     * @param $start 开始时间
     * @param $end 结束时间
     * @return array
     */
    public function index($start,$end)
    {
        if($start && $end)
        {
            $user_login_logs = Db::table('user_login_log')->where('login_time','between',"$start,$end")->order(['login_time'=>'desc'])->paginate('10');
        }elseif ($start){
            $user_login_logs = Db::table('user_login_log')->where('login_time','>',"$start")->order(['login_time'=>'desc'])->paginate('10');
        }elseif($end){
            $user_login_logs = Db::table('user_login_log')->where('login_time','<',"$end")->order(['login_time'=>'desc'])->paginate('10');
        }else{
            $user_login_logs = Db::table('user_login_log')->order(['login_time'=>'desc'])->paginate('10');
        }
        return ['user_login_logs'=>$user_login_logs->all(),'pages'=>$user_login_logs];
    }

    /**
     * 会员登陆日志删除
     * @param $ids 日志id
     * @return int
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        $result = Db::table('user_login_log')->where('login_log_id','in',$ids)->delete();
        !$result && Tools::returnJson(500,'删除失败');
        return $result;
    }
}