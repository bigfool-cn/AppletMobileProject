<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/22
 * Time: 10:01
 */

namespace app\api\controller;

use app\api\logic\WxLoginLogic;
use app\helps\Tools;
use think\Controller;

class WxLogin extends Controller
{
    //登录逻辑类实例变量
    private $wxloginLogic;
    /**
     * 初始化登录逻辑类
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->wxloginLogic = new WxLoginLogic();
    }

    /**
     * 小程序用户登录
     */
    public function wxLogin()
    {
        $res = $this->wxloginLogic->wxLogin();
        Tools::returnJson($res['code'],$res['msg'],$res['data']);
    }

}