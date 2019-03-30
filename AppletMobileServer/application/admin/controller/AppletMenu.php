<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/11
 * Time: 14:19
 */

namespace app\admin\controller;


use app\admin\logic\AppletMenuLogic;
use app\helps\Tools;
use think\Controller;
use think\Db;

class AppletMenu extends Controller
{
    private $appletMenuLogic;
    public function __construct()
    {
        parent::__construct();
        $this->appletMenuLogic = new AppletMenuLogic();
    }

    /**
     * 小程序栏目列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $applet_menus = $this->appletMenuLogic->index();
        $this->assign('applet_menus',$applet_menus);
        return $this->fetch();
    }

    /**
     * 小程序栏目添加
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $this->appletMenuLogic->add($form);
            Tools::returnJson(200,'添加成功');
        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改小程序栏目
     * @param int $id 栏目id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update($id=0)
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $this->appletMenuLogic->update($id,$form);
            Tools::returnJson(200,'修改成功');
        }else{
            $applet_menu = $this->appletMenuLogic->update($id);
            $this->assign('applet_menu',$applet_menu);
            return $this->fetch();
        }
    }

    /**
     * 删除小程序栏目
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete()
    {
        $ids = $this->request->get('ids',0);
        $this->appletMenuLogic->delete($ids);
        Tools::returnJson(200,'删除成功');
    }

    /**
     *排序
     * @param int $id 栏目id
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function sort($id=0)
    {
        $this->appletMenuLogic->sort($id);
        Tools::returnJson(200,'更改排序成功');
    }

    public function open($id=0)
    {
        $this->appletMenuLogic->open($id);
        Tools::returnJson(200,'更改开放状态成功');
    }
}