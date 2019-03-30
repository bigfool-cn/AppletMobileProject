<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/1
 * Time: 10:17
 */

namespace app\admin\logic;


use app\admin\validate\GoodsHotValidate;
use app\helps\Tools;
use think\Db;

class GoodsHotLogic
{

    private $goodsCates,$goodsHotValidate; // 商品分类,商品
    public function __construct()
    {
        $this->goodsCates = Db::table('goods_cate')->field('goods_cate_id,goods_cate_name')->select();
        $this->goodsHotValidate = new GoodsHotValidate();
    }

    /**
     * 热销商品列表
     * @return array
     */
    public function index()
    {
        $goodsHots = Db::table('goods_hot')->alias('a')
            ->leftJoin('goods b','a.goods_id=b.goods_id')
            ->field('a.goods_hot_id,a.sort,a.create_time,b.update_time,b.goods_name,b.goods_image_urls')
            ->paginate(15);
        $goodsHotsAll = $goodsHots->all();
        $sortArr = array_column($goodsHotsAll,'sort');
        // 获取当前最大排序值
        $maxSort =  $sortArr ? max($sortArr) : 0;
        foreach ($goodsHotsAll as $key=>$value){
            $image_urls = explode(',',$value['goods_image_urls']);
            $goodsHotsAll[$key]['goods_image_url'] = Tools::cutImg2CdnImg($image_urls[0],320);
        }
        return ['goodsHots'=>$goodsHotsAll,'pages'=>$goodsHots,'maxSort'=>$maxSort];
    }

    /**
     * 添加热销商品
     * @param array $form 表单数据
     * @param string $method 请求方法
     * @return array
     */
    public function add($form=array(),$method='GET')
    {
        if($method == "POST"){
            if($this->goodsHotValidate->check($form)){
                $data = array(
                    'goods_id' => $form['goods_id'],
                    'create_time' => time(),
                    'update_time' => time()
                );
                $res = Db::table('goods_hot')->insert($data);
                if($res){
                    return ['code'=>200,'msg'=>'添加成功'];
                }else{
                    return ['code'=>500,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>401,'msg'=>$this->goodsHotValidate->getError()];
            }
        }else{
            $goods = Db::table('goods')->where(['goods_cate_id'=>$this->goodsCates[0]['goods_cate_id']])
                ->field('goods_id,goods_name')->select();
            return ['goodsCates'=>$this->goodsCates,'goods'=>$goods];
        }
    }

    /**
     * 修改热销商品
     * @param $id
     * @param array $form
     * @param string $method
     * @return array
     */
    public function update($id,$form=array(),$method='GET')
    {
        if ($method == 'POST'){
            if($this->goodsHotValidate->check($form)){
                $data = [
                    'goods_id' => $form['goods_id'],
                    'update_time' => time()
                ];
                $res = Db::table('goods_hot')->where(['goods_hot_id'=>$id])->update($data);
                if($res){
                    return ['code'=>200,'修改成功'];
                }else{
                    return ['code'=>500,'修改失败'];
                }
            }else{
                return ['code'=>401,$this->goodsHotValidate->getError()];
            }
        }else{
            $goodsHot = Db::table('goods_hot')->alias('a')
                ->leftJoin('goods b','a.goods_id=b.goods_id')
                ->where(['a.goods_hot_id'=>$id])
                ->field('a.goods_hot_id,a.goods_id,b.goods_cate_id')->find();
            $goods = Db::table('goods')->where(['goods_cate_id'=>$goodsHot['goods_cate_id']])
                ->field('goods_id,goods_name')->select();
            return ['goodsCates'=>$this->goodsCates,'goods'=>$goods,'goodsHot'=>$goodsHot];
        }
    }

    /**
     * 删除热销商品
     * @param $ids goods_hot_id串
     * @return array
     */
    public function delete($ids)
    {
        $idsArr = explode(',',$ids);
        $res = Db::table('goods_hot')->where(['goods_hot_id'=>$idsArr])->delete();
        if($res){
            return ['code'=>200,'msg'=>'删除成功'];
        }else{
            return ['code'=>500,'msg'=>'删除失败'];
        }
    }

    /**
     * 更新排序
     * @param $id goods_hot_id
     * @param $sort 排序值
     * @return array
     */
    public function sort($id,$sort)
    {
        $res = Db::table('goods_hot')->where(['goods_hot_id'=>$id])->update(['sort'=>$sort,'update_time'=>time()]);
        if($res){
            return ['code'=>200,'msg'=>'更新排序成功'];
        }else{
            return ['code'=>500,'msg'=>'更新排序失败'];
        }
    }
}