<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/29
 * Time: 11:54
 */

namespace app\admin\controller;

use app\admin\logic\UserLogic;
use app\helps\Tools;
use think\Controller;

class User extends Controller
{
    //会员逻辑实例
    private $userLogic;
    public function __construct()
    {
        parent::__construct();
        $this->userLogic = new UserLogic();
    }

    /**
     * 用户列表页
     * @return mixed
     */
    public function index()
    {
        //查询条件，开始时间
        $start = strtotime($this->request->get('start',0));
        //结束时间
        $end = strtotime($this->request->get('end',0));
        $result = $this->userLogic->index($start,$end);
        $this->assign('users',$result['users']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     *用户一周内访问次数
     * @param int $id
     */
    public function stat($id=0)
    {
        $result = $this->userLogic->stat($id);
        Tools::returnJson(200,'操作成功',$result);
    }
}