<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/19
 * Time: 20:31
 */

namespace app\admin\controller;


use app\admin\logic\UserRoleLogic;
use app\helps\Tools;
use think\App;
use think\Controller;

class UserRole extends Controller
{
    private $userRoleLogic;
    public function __construct()
    {
        parent::__construct();
        $this->userRoleLogic = new UserRoleLogic();
    }

    /**
     * 用户角色列表
     * @return mixed
     */
    public function index()
    {
        $res = $this->userRoleLogic->index();
        $this->assign('exclude',$res['exclude']);
        $this->assign('user_roles',$res['userRolesAll']);
        $this->assign('pages',$res['pages']);
        return $this->fetch();
    }

    /**
     * 添加用户角色
     * @return mixed
     */
    public function add()
    {
        if ($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->userRoleLogic->add($form,'POST');
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $res = $this->userRoleLogic->add();
            $this->assign('admin_users',$res['adminUsers']);
            $this->assign('roles',$res['roles']);
            return $this->fetch();
        }
    }

    /**
     * 修改用户角色
     * @param int $id
     * @return mixed
     */
    public function update($id=0)
    {
        !$id && Tools::returnJson(400,'参数错误');
        if ($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->userRoleLogic->update($id,$form,'POST');
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $res = $this->userRoleLogic->update($id);
            $this->assign('user_role',$res['user_role']);
            $this->assign('roles',$res['roles']);
            return $this->fetch();
        }
    }

    /**
     * 删除用户角色
     * @param string $ids
     */
    public function delete($ids='')
    {
        !$ids && Tools::returnJson(400,'参数错误');
        $res = $this->userRoleLogic->delete($ids);
        Tools::returnJson($res['code'],$res['msg']);
    }
}