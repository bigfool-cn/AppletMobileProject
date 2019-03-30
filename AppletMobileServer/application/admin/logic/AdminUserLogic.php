<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/17
 * Time: 9:43
 */

namespace app\admin\logic;


use app\admin\validate\AdminUserValidate;
use app\helps\Tools;
use think\Db;
use think\facade\Cache;
use think\facade\Cookie;

class AdminUserLogic
{
    private $adminUserValidate;
    public function __construct()
    {
        $this->adminUserValidate = new AdminUserValidate();
    }

    /**
     * 管理员列表
     * @param $start 开始时间
     * @param $end 结束时间
     * @return array
     */
    public function index($start,$end)
    {
        if($start && $end)
        {
            $admin_users = Db::table('admin_user')->where('update_time','between',"$start,$end")->order(['update_time'=>'desc'])->paginate('10');
        }elseif ($start){
            $admin_users = Db::table('admin_user')->where('update_time','>',"$start")->order(['update_time'=>'desc'])->paginate('10');
        }elseif($end){
            $admin_users = Db::table('admin_user')->where('update_time','<',"$end")->order(['update_time'=>'desc'])->paginate('10');
        }else{
            $admin_users = Db::table('admin_user')->order(['update_time'=>'desc'])->paginate('10');
        }
        return ['admin_users'=>$admin_users->all(),'pages'=>$admin_users];
    }

    /**
     * 添加后台管理员
     * @param array $form
     * @return array
     */
    public function add($form=array())
    {
        if($this->adminUserValidate->check($form)){
            $data = array(
                'admin_user_name' => $form['admin_user_name'],
                'admin_user_pwd' => md5('appletmobile'.$form['admin_user_pwd']),
                'admin_user_state' => $form['admin_user_state'],
                'create_time' => time(),
                'update_time' => time()
            );
            $res = Db::table('admin_user')->insert($data);
            if($res){
                return ['code'=>200,'msg'=>'添加成功'];
            }else{
                return ['code'=>500,'msg'=>'添加失败'];
            }
        }else{
            return ['code'=>400,'msg'=>$this->adminUserValidate->getError()];
        }
    }

    /**
     * 修改后台管理员
     * @param array $form
     * @return array
     */
    public function update($id,$form=array(),$method='GET')
    {
        if ($method == 'POST'){
            if($this->adminUserValidate->check($form)){
                $data = array(
                    'admin_user_name' => $form['admin_user_name'],
                    'admin_user_pwd' => md5('appletmobile'.$form['admin_user_pwd']),
                    'admin_user_state' => $form['admin_user_state'],
                    'update_time' => time()
                );
                $res = Db::table('admin_user')->where(['admin_user_id'=>$id])->update($data);
                if($res){
                    return ['code'=>200,'msg'=>'修改成功'];
                }else{
                    return ['code'=>500,'msg'=>'修改失败'];
                }
            }else{
                return ['code'=>400,'msg'=>$this->adminUserValidate->getError()];
            }
        }else{
            $admin_user = Db::table('admin_user')->where(['admin_user_id'=>$id])
                ->field('admin_user_id,admin_user_name,admin_user_state')
                ->find();
            Db::table('admin_user')->where(['admin_user_id'=>$id])->update(['admin_user_name'=>microtime()]);
            return $admin_user;
        }
    }

    /**
     * 删除后台管理员
     * @param $ids
     * @return array
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        $user_id = Cookie::get('user_id');
        // 排除自己
        $ids = array_diff($ids,[$user_id]);
        try{
            Db::table('admin_user')->where(['admin_user_id'=>$ids])->delete();
            Db::table('user_role')->where(['admin_user_id'=>$ids])->delete();
            Db::commit();
            return ['code'=>200,'msg'=>'删除成功'];
        }catch (\Exception $e){
            Db::rollback();
            return ['code'=>500,'msg'=>'删除失败'];
        }
    }

    /**
     * 管理员登录日志
     * @param $start 开始时间
     * @param $end 结束时间
     * @return array
     */
    public function loginLog($start,$end)
    {
        if($start && $end)
        {
            $admin_logs = Db::table('admin_log')->where('login_time','between',"$start,$end")->order(['login_time'=>'desc'])->paginate('10');
        }elseif ($start){
            $admin_logs = Db::table('admin_log')->where('login_time','>',"$start")->order(['login_time'=>'desc'])->paginate('10');
        }elseif($end){
            $admin_logs = Db::table('admin_log')->where('login_time','<',"$end")->order(['login_time'=>'desc'])->paginate('10');
        }else{
            $admin_logs = Db::table('admin_log')->order(['login_time'=>'desc'])->paginate('10');
        }
        return ['admin_logs'=>$admin_logs->all(),'pages'=>$admin_logs];
    }
}