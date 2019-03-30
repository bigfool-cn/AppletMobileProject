<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/19
 * Time: 9:44
 */

namespace app\admin\logic;


use app\admin\validate\GoodsValidate;
use app\helps\Swcs;
use app\helps\Tools;
use think\Db;

class GoodsLogic
{
    private $goodsCates,$goodsValidate;
    public function __construct()
    {
        $this->goodsCates = Db::table('goods_cate')->field('goods_cate_id,goods_cate_name')->all();
        $this->goodsValidate = new GoodsValidate();
    }

    /**
     * 商品列表
     * @return array
     */
    public function index($goods_name)
    {
        $query = Db::table('goods')->alias('a')
            ->leftJoin('goods_cate b','a.goods_cate_id=b.goods_cate_id');
        if ($goods_name){
            // 获取搜索值分词
            $words = Swcs::getWords($goods_name);
            $goodsIds = array();
            foreach ($words as $key=>$value){
                // 查在商品分词库找搜索分词符合的商品id
                $goods_ids = Db::table('goods_words')->where("FIND_IN_SET('$value',goods_words)")
                    ->column('goods_id');
                $goodsIds = array_merge($goodsIds,$goods_ids);
            }
            $query->where(['a.goods_id'=>$goodsIds]);
        }
        $goods = $query->order('a.update_time desc')
            ->field('a.goods_id,a.goods_name,a.goods_desc,a.goods_price,a.goods_sprice,a.goods_stock,
                a.goods_express,a.goods_image_urls,a.create_time,a.update_time,b.goods_cate_name')
            ->paginate('15');
        return ['goods'=>$goods->all(),'pages'=>$goods];
    }

    /**
     * 添加商品
     * @param array $form 表单数据
     * @param string $method 请求方法
     */
    public function add($form=array(),$method="GET")
    {
        if($method == "POST"){
            if($this->goodsValidate->check($form)){
                $data = array(
                    'goods_cate_id' => $form['goods_cate_id'],
                    'goods_name' => $form['goods_name'],
                    'goods_desc' => $form['goods_desc'],
                    'goods_price' => $form['goods_price'],
                    'goods_sprice' => $form['goods_sprice'],
                    'goods_image_urls' => $form['goods_images'],
                    'goods_stock' => $form['goods_stock'],
                    'goods_express' => $form['goods_express'],
                    'create_time' => time(),
                    'update_time' => time()
                );
                // 启动事务
                Db::startTrans();
                try {
                    $goods_id = Db::table('goods')->insertGetId($data);
                    $data_py = array(
                        'goods_detail' => $form['goods_detail'],
                        'goods_param' => $form['goods_param'],
                        'goods_id' => $goods_id
                    );
                    $words = Swcs::getWords($form['goods_desc']);
                    $wordsData = array(
                        'goods_words' => implode(',',$words),
                        'goods_id' => $goods_id,
                        'create_time' => time(),
                        'update_time' => time()
                    );
                    Db::table('goods_detail_param')->insert($data_py);
                    Db::table('goods_words')->insert($wordsData);
                    // 提交事务
                    Db::commit();
                    return ['code'=>200,'msg'=>'添加成功'];
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return ['code'=>500,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>500,'msg'=>$this->goodsValidate->getError()];
            }
        }else{
            return $this->goodsCates;
        }
    }

    /**
     * 修改商品
     * @param $id 商品id
     * @param array $form 表单数据
     * @param string $method 请求方法
     * @return array
     */
    public function update($id,$form=array(),$method="GET")
    {
        if($method == "POST"){
            if($this->goodsValidate->check($form)){
                $data = array(
                    'goods_cate_id' => $form['goods_cate_id'],
                    'goods_name' => $form['goods_name'],
                    'goods_desc' => $form['goods_desc'],
                    'goods_price' => $form['goods_price'],
                    'goods_sprice' => $form['goods_sprice'],
                    'goods_image_urls' => $form['goods_images'],
                    'goods_stock' => $form['goods_stock'],
                    'goods_express' =>$form['goods_express'],
                    'update_time' => time()
                );
                // 启动事务
                Db::startTrans();
                try {
                    Db::table('goods')->where(['goods_id'=>$id])->update($data);
                    $data_py = array(
                        'goods_detail' => $form['goods_detail'],
                        'goods_param' => $form['goods_param'],
                    );
                    $words = Swcs::getWords($form['goods_desc']);
                    $wordsData = array(
                        'goods_words' => implode(',',$words),
                        'update_time' => time()
                    );
                    Db::table('goods_detail_param')->where(['goods_id'=>$id])->update($data_py);
                    Db::table('goods_words')->where(['goods_id'=>$id])->update($wordsData);
                    // 提交事务
                    Db::commit();
                    return ['code'=>200,'msg'=>'修改成功'];
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return ['code'=>500,'msg'=>$e->getMessage()];
                }
            }else{
                return ['code'=>500,'msg'=>$this->goodsValidate->getError()];
            }
        }else{
            $goods = Db::table('goods')->alias('a')
                ->leftJoin('goods_detail_param b','a.goods_id=b.goods_id')
                ->where(['a.goods_id'=>$id])->find();
            $goods["goods_image_urls"] = explode(',',$goods["goods_image_urls"]);
            return ['goods'=>$goods,'goodsCates'=>$this->goodsCates];
        }
    }

    /**
     * 删除商品
     * @param $id 商品id
     * @return array
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        // 启动事务
        Db::startTrans();
        try {
            // 获取轮播图地址
            $goods_image_urls = Db::table('goods')->where(['goods_id'=>$ids])->column('goods_image_urls');
            $image_urls = array();
            foreach ($goods_image_urls as $key=>$goods_image_url){
                $urls = explode(',',$goods_image_url);
                $image_urls = array_merge($image_urls,$urls);
            }
            Db::table('goods')->where(['goods_id'=>$ids])->delete();
            Db::table('goods_detail_param')->where(['goods_id'=>$ids])->delete();
            Db::table('goods_words')->where(['goods_id'=>$ids])->delete();
            // 提交事务
            Db::commit();
            foreach ($image_urls as $key=>$goods_image_url){
                // 当值url经过编码，导致删除失败
                $url = urldecode($goods_image_url);
                // 删除腾讯云cos上的图片
                Tools::cosDeleteImg($url);
            }
            return ['code'=>200,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['code'=>500,'msg'=>'删除失败'];
        }
    }

    /**
     * 根据商品分类返回商品
     * @param $id 商品分类id
     * @return mixed
     */
    public function goodsList($id)
    {
        $goods = Db::table('goods')->where(['goods_cate_id'=>$id])
            ->field('goods_id,goods_name')->select();
        return $goods;
    }
}