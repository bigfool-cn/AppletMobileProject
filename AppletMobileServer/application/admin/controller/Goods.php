<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/19
 * Time: 9:44
 */

namespace app\admin\controller;


use app\admin\logic\GoodsLogic;
use app\helps\Tools;
use think\Controller;

class Goods extends Controller
{

    private $goodsLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsLogic = new GoodsLogic();
    }

    /**
     * 商品列表
     * @return mixed
     */
    public function index($goods_name='')
    {
        $result = $this->goodsLogic->index($goods_name);
        $this->assign('goods_name',$goods_name);
        $this->assign('goods',$result['goods']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     * 添加商品
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据不能为空');
            $result = $this->goodsLogic->add($form,'POST');
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $goodsCates = $this->goodsLogic->add();
            $this->assign('goodsCates',$goodsCates);
            return $this->fetch();
        }
    }

    /**
     * 修改商品
     * @param int $id 商品id
     * @return mixed
     */
    public function update($id=0)
    {
        !$id && Tools::returnJson(401,'参数错误');
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(400,'提交数据不能为空');
            $result = $this->goodsLogic->update($id,$form,'POST');
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $result = $this->goodsLogic->update($id);
            $this->assign('goods',$result['goods']);
            $this->assign('goodsCates',$result['goodsCates']);
            return $this->fetch();
        }
    }

    /**
     * 删除商品
     * @param int $id 商品id
     */
    public function delete($ids=0)
    {
        !$ids && Tools::returnJson(401,'参数错误');
        $result = $this->goodsLogic->delete($ids);
        Tools::returnJson($result['code'],$result['msg']);
    }

    /**
     * 根据商品分类获取商品列表
     */
    public function goodsList()
    {
        $goods_cate_id = $this->request->post('goods_cate_id');
        !$goods_cate_id && Tools::returnJson(401,'参数错误');
        $goods = $this->goodsLogic->goodsList($goods_cate_id);
        Tools::returnJson(200,'成功',$goods);
    }
}