<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/18
 * Time: 15:03
 */

namespace app\admin\logic;


use app\admin\validate\LoginValidate;
use app\helps\Tools;
use think\Controller;
use think\Db;
use think\facade\Cache;
use think\facade\Cookie;

class LoginLogic extends Controller
{
    private $loginValidate;
    public function __construct()
    {
        parent::__construct();
        $this->loginValidate = new LoginValidate();
    }

    /**
     * 登录
     * @return mixed
     */
    public function toLogin($form)
    {
        //验证表单数据
        if(!$this->loginValidate->check($form)){
            return array('code'=>400,'msg'=>$this->loginValidate->getError());
        }else{
            $user = Db::table('admin_user')->where(['admin_user_name'=>$form['username']])->find();
            if(!$user){
                return array('code'=>400,'msg'=>'用户不存在');
            }else if ($user['admin_user_state'] == 0){
                return array('code'=>400,'msg'=>'用户未激活');
            }else if($user['admin_user_pwd'] !== md5('appletmobile'.$form['password'])){
                return array('code'=>400,'msg'=>'密码错误');
            }
            $ip = $this->request->ip();
            $data = [
                'username' => $form['username'],
                'ip' => $ip
            ];
            Tools::curl(config('admin_host').'/saveipconfig','post',$data);
            Cookie::set('user',$form['username'],60*60*8);
            Cache::store('redis')->set($form['username'],$form['username'],60*60*8);
            Cookie::set('user_id',$user['admin_user_id'],60*60*8);
            Cache::store('redis')->set($user['admin_user_id'],$user['admin_user_id'],60*60*8);
            return array('code'=>200,'msg'=>'登录成功');
        }
    }

    /**
     * 登出
     */
    public function toLogout()
    {
        Cache::store('redis')->rm('user');
        header('location:/login');
    }
}