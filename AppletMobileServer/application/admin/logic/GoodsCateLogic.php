<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 11:10
 */

namespace app\admin\logic;


use app\admin\validate\GoodsCateValidate;
use think\Controller;
use think\Db;

class GoodsCateLogic extends Controller
{
    private $goodsCateValidate;
    private $rootCate; //根分类
    public function __construct()
    {
        parent::__construct();
        $goods_cates = Db::table('goods_cate')->where(['parent_id'=>0])->field('goods_cate_id,goods_cate_name')->all();
        $data = array((['goods_cate_id'=>0,'goods_cate_name'=>'根分类']));
        $this->rootCate = array_merge($data,$goods_cates);
        $this->goodsCateValidate = new GoodsCateValidate();
    }

    /**
     * 商品分类列表页
     * @return array
     */
    public function index()
    {
        $goods_cate = Db::table('goods_cate')->order(['sort'=>'desc','parent_id'=>'asc'])->paginate(15);
        $goods_cates = $goods_cate->all();
        $cates = array();
        foreach($goods_cates as $key=>$value) {
            if($value['parent_id'] == 0 && !array_key_exists($value['goods_cate_id'],$cates)){
                $cates[$value['goods_cate_id']] = $value;
                $cates[$value['goods_cate_id']]['child'] = array();
            }else {
                $cates[$value['parent_id']]['child'][] = $value;
            }
        }
        $goods_cates = array_values($cates);
        return ['goods_cates'=>$goods_cates,'pages'=>$goods_cate];
    }

    /**
     * 商品分类添加
     * @param array $form 表单数据
     */
    public function add($form=array())
    {
        if($this->request->isPost()){
            if($this->goodsCateValidate->check($form)){
                $data = array_merge($form, array('create_time' => time(), 'update_time' => time()));
                $res = Db::table('goods_cate')->insert($data);
                if($res){
                    return ['code'=>200,'msg'=>'添加成功'];
                }else{
                    return ['code'=>500,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>500,'msg'=>$this->goodsCateValidate->getError()];
            }
        }else{
            $rootCates = $this->rootCate;
            return $rootCates;
        }
    }

    /**
     * 修改商品分类
     * @param $id 分类id
     * @param array $form 表单数据
     * @return array
     */
    public function update($id,$form=array())
    {
        if($this->request->isPost()){
            if($this->goodsCateValidate->check($form)){
                $data = array_merge($form, array('update_time' => time()));
                $res = Db::table('goods_cate')->where(['goods_cate_id'=>$id])->update($data);
                if($res){
                    return ['code'=>200,'msg'=>'修改成功'];
                }else{
                    return ['code'=>500,'msg'=>'修改失败'];
                }
            }else{
                return ['code'=>500,'msg'=>$this->goodsCateValidate->getError()];
            }
        }else{
            $goodsCate = Db::table('goods_cate')->where(['goods_cate_id'=>$id])->find();
            return ['rootCate'=>$this->rootCate,'goodsCate'=>$goodsCate];
        }
    }

    /**
     * 商品分类删除
     * @param $ids goods_cate_id 串
     * @return int
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        $num = 0;
        foreach ($ids as $key=>$id){
            $parent_id = Db::table('goods_cate')->where(['goods_cate_id'=>$id])->value('parent_id');
            if ($parent_id == 0){
                $num += Db::table('goods_cate')->where(['goods_cate_id'=>$id])->whereOr(['parent_id'=>$id])->delete();
            }else{
                $num += Db::table('goods_cate')->where(['goods_cate_id'=>$id])->delete();
            }
        }
        return $num;
    }

    /**
     * 排序
     * @param $id goods_cate_id
     * @return int|string
     */
    public function sort($id)
    {
        $goods_cate = Db::table('goods_cate')->where(['goods_cate_id'=>$id])->field('sort')->find();
        $num = Db::table('goods_cate')->where(['goods_cate_id'=>$id])->update(['sort'=>$goods_cate['sort']+1]);
        return $num;
    }
}