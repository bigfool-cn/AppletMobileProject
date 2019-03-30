<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/11
 * Time: 18:43
 */

namespace app\admin\controller;


use app\admin\logic\GoodsSkillLogic;
use app\helps\Tools;
use think\Controller;

class GoodsSkill extends Controller
{
    private $goodsSkillLogic;
    public function __construct()
    {
        parent::__construct();
        $this->goodsSkillLogic = new GoodsSkillLogic();
    }

    /**
     * 商品列表
     * @return mixed
     */
    public function index($goods_skill_name='')
    {
        $result = $this->goodsSkillLogic->index($goods_skill_name);
        $this->assign('goods_skill_name',$goods_skill_name);
        $this->assign('goodsSkill',$result['goodsSkill']);
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
            $result = $this->goodsSkillLogic->add($form,'POST');
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $goodsCates = $this->goodsSkillLogic->add();
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
            $result = $this->goodsSkillLogic->update($id,$form,'POST');
            Tools::returnJson($result['code'],$result['msg']);
        }else{
            $result = $this->goodsSkillLogic->update($id);
            $this->assign('goodsSkill',$result['goodsSkill']);
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
        $result = $this->goodsSkillLogic->delete($ids);
        Tools::returnJson($result['code'],$result['msg']);
    }

}