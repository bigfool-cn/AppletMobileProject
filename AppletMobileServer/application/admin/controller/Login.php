<?php
/**
 * Created by PhpStorm.
 * User: ppchair
 * Date: 2018/11/16
 * Time: 15:47
 */

namespace app\admin\controller;

use app\helps\Tools;
use think\Controller;
use app\admin\logic\LoginLogic;
use think\Db;

class Login extends Controller
{

    //登录逻辑类实例变量
    private $loginLogic;
    /**
     * 初始化登录逻辑类
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->loginLogic = new LoginLogic();
    }

    /**
     * 登录
     */
    public function login()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $result = $this->loginLogic->toLogin($form);
            Tools::returnJson($result['code'],$result['msg']);
        }
        return $this->fetch();
    }

    /**
     * 登出
     */
    public function logout()
    {
        $this->loginLogic->toLogout();
    }

    /**
     * 异步获取IP地址信息并保存
     * @return bool
     */
    public function saveIpConfig()
    {
        //不等待返回响应，实现异步任务
        fastcgi_finish_request();
        $username = $this->request->post('username');
        $ip = $this->request->post('ip');
        $ip_addr = getIpConfig($ip);
        $data = [
            'username' => $username,
            'ip' => $ip,
            'address' => $ip_addr['data']['region'].'--'.$ip_addr['data']['city'],
            'login_time' => time()
        ];
        Db::table('admin_log')->insert($data);
        return true;
    }
}