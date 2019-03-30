<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/17
 * Time: 11:11
 */

namespace app\admin\controller;

use app\admin\logic\UserLoginLogLogic;
use app\helps\Tools;
use think\Controller;

class UserLoginLog extends Controller
{
    private $userLoginLogLogic;
    public function __construct()
    {
        parent::__construct();
        $this->userLoginLogLogic = new UserLoginLogLogic();
    }

    /**
     * 会员登录日志
     * @return mixed
     */
    public function index()
    {
        //查询条件，开始时间
        $start = strtotime($this->request->get('start',0));
        //结束时间
        $end = strtotime($this->request->get('end',0));
        $result = $this->userLoginLogLogic->index($start,$end);
        $this->assign('user_login_logs',$result['user_login_logs']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     * 会员登陆日志删除
     * @param int $ids
     */
    public function delete($ids=0)
    {
        $this->userLoginLogLogic->delete($ids);
        Tools::returnJson(200,'删除成功');
    }
}