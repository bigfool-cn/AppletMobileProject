<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 11:09
 */

namespace app\admin\controller;


use app\admin\logic\AdminMenuLogic;
use app\helps\Tools;
use think\Controller;
use think\Db;

class AdminMenu extends Controller
{
    private $adminMenuLogic;
    public function __construct()
    {
        parent::__construct();
        $this->adminMenuLogic = new AdminMenuLogic();
    }

    /**
     * 后台栏目列表页
     * @return mixed
     */
    public function index()
    {
        $result = $this->adminMenuLogic->index();
        $this->assign('admin_menus',$result['admin_menus']);
        $this->assign('total',$result['total']);
        return $this->fetch();
    }

    /**
     * 后台栏目添加
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $result = $this->adminMenuLogic->add($form);
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $result = $this->adminMenuLogic->add();
            $this->assign('rootMenus',$result);
            return $this->fetch();
        }
    }

    /**
     * 修改后台栏目
     * @param int $id 栏目id
     * @return mixed
     */
    public function update($id=0)
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $result = $this->adminMenuLogic->update($id,$form);
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $result = $this->adminMenuLogic->update($id);
            $this->assign('rootMenus',$result['rootMenu']);
            $this->assign('menu',$result['menu']);
            return $this->fetch();
        }
    }

    /**
     * 后台栏目删除
     * @param int $ids admin_menu_id 串
     */
    public function delete($ids=0)
    {
        !$ids && Tools::returnJson(401,'参数错误');
        $num = $this->adminMenuLogic->delete($ids);
        !$num && Tools::returnJson(400,'删除失败');
        Tools::returnJson(200,'删除成功');
    }

    /**
     * 后台栏目排序
     * @param int $id admin_menu_id
     */
    public function sort($id=0)
    {
        !$id && Tools::returnJson(401,'参数错误');
        $num = $this->adminMenuLogic->sort($id);
        !$num && Tools::returnJson(400,'更新排序失败');
        Tools::returnJson(200,'更新排序成功');
    }
}