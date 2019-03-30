<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 11:09
 */

namespace app\admin\controller;


use app\admin\logic\GoodsCateLogic;
use app\helps\Tools;
use think\Controller;

class GoodsCate extends Controller
{
    private $goodsCateLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsCateLogic = new GoodsCateLogic();
    }

    /**
     * 商品分类列表页
     * @return mixed
     */
    public function index()
    {
        $result = $this->goodsCateLogic->index();
        $this->assign('goods_cates',$result['goods_cates']);
        $this->assign('pages',$result['pages']);
        return $this->fetch();
    }

    /**
     * 商品分类添加
     * @return mixed
     */
    public function add()
    {
        if($this->request->isPost()){
            $form = $this->request->post();
            $result = $this->goodsCateLogic->add($form);
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $result = $this->goodsCateLogic->add();
            $this->assign('rootCates',$result);
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
            $result = $this->goodsCateLogic->update($id,$form);
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $result = $this->goodsCateLogic->update($id);
            $this->assign('rootCates',$result['rootCate']);
            $this->assign('goodsCate',$result['goodsCate']);
            return $this->fetch();
        }
    }

    /**
     * 商品分类删除
     * @param int $ids admin_menu_id 串
     */
    public function delete($ids=0)
    {
        !$ids && Tools::returnJson(401,'参数错误');
        $num = $this->goodsCateLogic->delete($ids);
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
        $num = $this->goodsCateLogic->sort($id);
        !$num && Tools::returnJson(400,'更新排序失败');
        Tools::returnJson(200,'更新排序成功');
    }
}