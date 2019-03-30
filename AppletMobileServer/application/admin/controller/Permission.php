<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/19
 * Time: 20:29
 */

namespace app\admin\controller;


use app\admin\logic\PermissionLogic;
use app\helps\Tools;
use think\Controller;

class Permission extends Controller
{
    private $permissionLogic;
    public function __construct()
    {
        parent::__construct();
        $this->permissionLogic = new PermissionLogic();
    }

    /**
     * 权限列表
     * @return mixed
     */
    public function index()
    {
        $res = $this->permissionLogic->index();
        $this->assign('permissions',$res['permissionsAll']);
        $this->assign('pages',$res['pages']);
        return $this->fetch();
    }

    /**
     * 添加权限
     * @return mixed
     */
    public function add()
    {
        if ($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据为空');
            $res = $this->permissionLogic->add($form);
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            return $this->fetch();
        }
    }

    /**
     * 权限
     * @param string $ids
     */
    public function delete($ids='')
    {
        !$ids && Tools::returnJson(400,'参数错误');
        $res = $this->permissionLogic->delete($ids);
        Tools::returnJson($res['code'],$res['msg']);
    }
}