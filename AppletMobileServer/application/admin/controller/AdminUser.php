<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/17
 * Time: 9:43
 */

namespace app\admin\controller;

use app\admin\logic\AdminUserLogic;
use app\helps\Tools;
use think\Controller;

class AdminUser extends Controller
{
    private $adminUserLogic;
    public function __construct()
    {
        parent::__construct();
        $this->adminUserLogic = new AdminUserLogic();
    }

    /**
     * 管理员列表
     * @return mixed
     */
    public function index()
    {
        //查询条件，开始时间
        $start = strtotime($this->request->get('start',0));
        //结束时间
        $end = strtotime($this->request->get('end',0));
        $result = $this->adminUserLogic->index($start,$end);
        $this->assign('admin_users',$result['admin_users']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     * 添加后台管理员
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->adminUserLogic->add($form);
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            return $this->fetch();
        }
    }

    /**
     * 添加后台管理员
     * @return mixed
     */
    public function update($id=0)
    {
        !$id && Tools::returnJson(400,'参数错误');
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->adminUserLogic->update($id,$form,'POST');
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $admin_user = $this->adminUserLogic->update($id);
            $this->assign('admin_user',$admin_user);
            return $this->fetch();
        }
    }

    /**
     * 删除后台管理员
     * @param $ids
     */
    public function delete($ids)
    {
        !$ids && Tools::returnJson(400,'参数错误');
        $res = $this->adminUserLogic->delete($ids);
        Tools::returnJson($res['code'],$res['msg']);
    }

    /**
     * 管理员登陆日志
     */
    public function loginLog()
    {
        //查询条件，开始时间
        $start = strtotime($this->request->get('start',0));
        //结束时间
        $end = strtotime($this->request->get('end',0));
        $result = $this->adminUserLogic->loginLog($start,$end);
//        var_dump($result['pages']);die;
        $this->assign('admin_logs',$result['admin_logs']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }
}