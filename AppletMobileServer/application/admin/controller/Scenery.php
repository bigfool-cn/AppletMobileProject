<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/29
 * Time: 16:58
 */

namespace app\admin\controller;

use app\admin\logic\SceneryLogic;
use app\helps\Tools;
use think\Controller;

class Scenery extends Controller
{
    //风景逻辑实例
    private $sceneryLogic;
    public function __construct()
    {
        parent::__construct();
        $this->sceneryLogic = new SceneryLogic();
    }

    /**
     * 风景列表
     * @throws \think\exception\DbException
     */
    public function index()
    {
        //查询条件，开始时间
        $start = strtotime($this->request->get('start',0));
        //结束时间
        $end = strtotime($this->request->get('end',0));
        $result = $this->sceneryLogic->index($start,$end);
        $this->assign('scenerys',$result['scenerys']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     * 添加风景
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost())
        {
            $form = $this->request->post();
            $this->sceneryLogic->add($form);
            Tools::returnJson(200,'添加成功');
        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改风景
     */
    public function update($id=0)
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $this->sceneryLogic->update($id,$form);
            Tools::returnJson(200,'修改成功');
        }else{
            $scenery = $this->sceneryLogic->update($id);
            $this->assign('scenery',$scenery);
            return $this->fetch();
        }

    }

    /**
     * 更改风景发布状态
     */
    public function state()
    {
        $id = $this->request->get('id',0);
        $state = $this->request->get('state','');
        $data = $this->sceneryLogic->state($id,$state);
        Tools::returnJson(200,'更改状态成功',$data);
    }

    public function delete($ids=0)
    {
        $this->sceneryLogic->delete($ids);
        Tools::returnJson(200,'删除成功');
    }
}