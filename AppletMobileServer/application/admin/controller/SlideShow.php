<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/22
 * Time: 14:36
 */

namespace app\admin\controller;


use app\admin\logic\SlideShowLogic;
use app\helps\Tools;
use think\Controller;

class SlideShow extends Controller
{
    private $slideShowLogic;

    public function __construct()
    {
        parent::__construct();
        $this->slideShowLogic = new SlideShowLogic();
    }

    /**
     * 商城首页轮播图列表
     * @return mixed
     */
    public function index()
    {
        $result = $this->slideShowLogic->index();
        $this->assign('slide_shows', $result['slideShows']);
        $this->assign('pages', $result['pages']);
        $this->assign('max_sort',$result['maxSort']);
        return $this->fetch();
    }

    /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $form = $this->request->post();
            !$form && Tools::returnJson(401,'提交数据为空');
            $reslut = $this->slideShowLogic->add($form,"POST");
            Tools::returnJson($reslut['code'],$reslut['msg']);
        } else {
            $result = $this->slideShowLogic->add();
            $this->assign('goods_cates', $result['goodsCates']);
            $this->assign('goods', $result['goods']);
            return $this->fetch();
        }
    }

    /**
     * 修改首夜轮播图
     * @param int $id
     * @return mixed
     */
    public function update($id=0)
    {
        !$id && Tools::returnJson(401,'参数错误');
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(401,'提交数据为空');
            $res = $this->slideShowLogic->update($id,$form,'POST');
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $res = $this->slideShowLogic->update($id);
            $this->assign('slide_show',$res['slideShow']);
            $this->assign('goods_cates', $res['goodsCates']);
            $this->assign('goods',$res['goods']);
            return $this->fetch();
        }
    }

    /**
     * 删除首页轮播图
     * @param $ids
     */
    public function delete($ids='')
    {
        !$ids && Tools::returnJson(401,'参数错误');
        $res = $this->slideShowLogic->delete($ids);
        Tools::returnJson($res['code'],$res['msg']);
    }

    /**
     * 更新排序
     * @param int $id slide_id
     * @param null $sort 排序值
     */
    public function sort($id=0,$sort=null)
    {
        (!$id || $sort==null) && Tools::returnJson(401,'参数错误');
        $res = $this->slideShowLogic->sort($id,$sort);
        Tools::returnJson($res['code'],$res['msg']);
    }

}