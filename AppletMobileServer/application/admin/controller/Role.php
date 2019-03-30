<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/8
 * Time: 9:59
 */

namespace app\admin\controller;


use app\admin\logic\RoleLogic;
use app\helps\Tools;
use think\Controller;

class Role extends Controller
{

    private $roleLogic;
    public function __construct()
    {
        parent::__construct();
        $this->roleLogic = new RoleLogic();
    }

    /**
     * 角色列表
     * @return mixed
     */
    public function index()
    {
        $result = $this->roleLogic->index();
        $this->assign('exclude',$result['exclude']);
        $this->assign('roles',$result['roles']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     * 添加角色
     * @return mixed
     */
    public function add()
    {
        if ($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->roleLogic->add('POST',$form);
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $permissions = $this->roleLogic->add();
            $this->assign('permissions',$permissions);
            return $this->fetch();
        }
    }

    /**
     * 修改角色
     * @return mixed
     */
    public function update($id=0)
    {
        !$id && Tools::returnJson(400,'参数错误');
        if ($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->roleLogic->update($id,'POST',$form);
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $res = $this->roleLogic->update($id);
            $this->assign('role',$res['role']);
            $this->assign('permissions',$res['permissions']);
            return $this->fetch();
        }
    }

    /**
     * 删除角色
     * @param $ids
     */
    public function delete($ids)
    {
        !$ids && Tools::returnJson(400,'参数错误');
        $res = $this->roleLogic->delete($ids);
        Tools::returnJson($res['code'],$res['msg']);
    }
}