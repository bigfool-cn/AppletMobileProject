<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/1
 * Time: 10:16
 */

namespace app\admin\controller;


use app\admin\logic\GoodsHotLogic;
use app\helps\Tools;
use think\Controller;

class GoodsHot extends Controller
{
    private $goodsHotLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsHotLogic = new GoodsHotLogic();
    }

    /**
     * 热销商品列表
     * @return mixed
     */
    public function index()
    {
        $res = $this->goodsHotLogic->index();
        $this->assign('goods_hots',$res['goodsHots']);
        $this->assign('pages',$res['pages']);
        $this->assign('max_sort',$res['maxSort']);
        return $this->fetch();
    }

    /**
     * 添加热销商品
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(401,'参数错误');
            $res = $this->goodsHotLogic->add($form,"POST");
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $res = $this->goodsHotLogic->add();
            $this->assign('goods_cates',$res['goodsCates']);
            $this->assign('goods',$res['goods']);
            return $this->fetch();
        }
    }

    /**
     * 修改热销商品
     * @param int $id goods_hot_id
     * @return mixed
     */
    public function update($id=0)
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            !$id && Tools::returnJson(401,'参数错误');
            !$form && Tools::returnJson(401,'提交数据为空');
            $res = $this->goodsHotLogic->update($id,$form,'POST');
            Tools::returnJson($res['code'],$res['msg']);
        }else{
            $res = $this->goodsHotLogic->update($id);
            $this->assign('goods_cates',$res['goodsCates']);
            $this->assign('goods',$res['goods']);
            $this->assign('goods_hot',$res['goodsHot']);
            return $this->fetch();
        }
    }

    /**
     * 删除热销商品
     * @param string $ids goods_hot_id串
     */
    public function delete($ids='')
    {
        !$ids && Tools::returnJson(401,'参数错误');
        $res = $this->goodsHotLogic->delete($ids);
        Tools::returnJson($res['code'],$res['msg']);
    }

    /**
     * 更新排序
     * @param int $id goods_hot_id
     * @param null $sort 排序值
     */
    public function sort($id=0,$sort=null)
    {
        (!$id || $sort==null) && Tools::returnJson(401,'参数错误');
        $res = $this->goodsHotLogic->sort($id,$sort);
        Tools::returnJson($res['code'],$res['msg']);
    }
}