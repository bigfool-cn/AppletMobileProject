<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/11
 * Time: 18:45
 */

namespace app\admin\logic;


use app\admin\validate\GoodsSkillValidate;
use app\helps\RedisHelper;
use app\helps\Tools;
use think\Db;

class GoodsSkillLogic
{
    private $goodsCates,$goodsSkillValidate,$redis;
    public function __construct()
    {
        $this->goodsCates = Db::table('goods_cate')->field('goods_cate_id,goods_cate_name')->all();
        $this->goodsSkillValidate = new GoodsSkillValidate();
        $this->redis = $redis = new RedisHelper();
    }

    /**
     * 秒杀商品列表
     * @return array
     */
    public function index($goods_skill_name='')
    {
        $query =  Db::table('goods_skill')->alias('a')
            ->leftJoin('goods_cate b','a.goods_cate_id=b.goods_cate_id');
        $goods_skill_name && $query = $query->where('goods_skill_desc','like','%'.$goods_skill_name.'%');
        $goodsSkill = $query->order('a.update_time desc')
            ->field('a.goods_skill_id,a.goods_skill_name,a.goods_skill_desc,a.goods_skill_price,
                a.goods_skill_sprice,a.goods_skill_stock,a.goods_skill_express,a.goods_skill_image_urls,
                a.goods_skill_time,a.is_end,a.create_time,a.update_time,b.goods_cate_name')
            ->paginate('15');
        return ['goodsSkill'=>$goodsSkill->all(),'pages'=>$goodsSkill];
    }

    /**
     * 添加秒杀商品
     * @param array $form 表单数据
     * @param string $method 请求方法
     */
    public function add($form=array(),$method="GET")
    {
        if($method == "POST"){
            if($this->goodsSkillValidate->check($form)){
                $data = array(
                    'goods_cate_id' => $form['goods_cate_id'],
                    'goods_skill_name' => $form['goods_skill_name'],
                    'goods_skill_desc' => $form['goods_skill_desc'],
                    'goods_skill_price' => $form['goods_skill_price'],
                    'goods_skill_sprice' => $form['goods_skill_sprice'],
                    'goods_skill_image_urls' => $form['goods_skill_images'],
                    'goods_skill_stock' => $form['goods_skill_stock'],
                    'goods_skill_express' => $form['goods_skill_express'],
                    'goods_skill_detail' => $form['goods_skill_detail'],
                    'goods_skill_param' => $form['goods_skill_param'],
                    'goods_skill_time' => $form['goods_skill_time'],
                    'create_time' => time(),
                    'update_time' => time()
                );

                $goods_skill_id = Db::table('goods_skill')->insertGetId($data);
                if($goods_skill_id){
                    // 库存入redis队列
                    for ($i = 0; $i < $form['goods_skill_stock']; $i++){
                        $this->redis->lpush('goods_skill_'.$goods_skill_id,$i);
                    }
                    return ['code'=>200,'msg'=>'添加成功'];
                }else{
                    return ['code'=>500,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>500,'msg'=>$this->goodsSkillValidate->getError()];
            }
        }else{
            return $this->goodsCates;
        }
    }

    /**
     * 修改秒杀商品
     * @param $id 商品id
     * @param array $form 表单数据
     * @param string $method 请求方法
     * @return array
     */
    public function update($id,$form=array(),$method="GET")
    {
        if ($method == "POST") {
            if ($this->goodsSkillValidate->check($form)) {
                $data = array(
                    'goods_cate_id' => $form['goods_cate_id'],
                    'goods_skill_name' => $form['goods_skill_name'],
                    'goods_skill_desc' => $form['goods_skill_desc'],
                    'goods_skill_price' => $form['goods_skill_price'],
                    'goods_skill_sprice' => $form['goods_skill_sprice'],
                    'goods_skill_image_urls' => $form['goods_skill_images'],
                    'goods_skill_stock' => $form['goods_skill_stock'],
                    'goods_skill_express' => $form['goods_skill_express'],
                    'goods_skill_detail' => $form['goods_skill_detail'],
                    'goods_skill_param' => $form['goods_skill_param'],
                    'goods_skill_time' => $form['goods_skill_time'],
                    'update_time' => time()
                );

                $res = Db::table('goods_skill')->where(['goods_skill_id' => $id])->update($data);
                if($res){
                    // 清空原有库存
                    $this->redis->ltrim('goods_skill_'.$id,1,0);
                    // 库存入redis队列
                    for ($i = 0; $i < $form['goods_skill_stock']; $i++){
                        $this->redis->lpush('goods_skill_'.$id,$i);
                    }
                    return ['code' => 200, 'msg' => '修改成功'];
                }else{
                    return ['code' => 500, 'msg' => '修改失败'];
                }
            } else {
            return ['code' => 500, 'msg' => $this->goodsSkillValidate->getError()];
        }
        }else{
            $goodsSkill = Db::table('goods_skill')->where(['goods_skill_id'=>$id])->find();
            $goodsSkill["goods_skill_image_urls"] = explode(',',$goodsSkill["goods_skill_image_urls"]);
            return ['goodsSkill'=>$goodsSkill,'goodsCates'=>$this->goodsCates];
        }
    }

    /**
     * 删除秒杀商品
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
            $goods_skill_image_urls = Db::table('goods_skill')->where(['goods_skill_id'=>$ids])
                ->column('goods_skill_image_urls');
            $image_urls = array();
            foreach ($goods_skill_image_urls as $key=>$goods_skill_image_url){
                $urls = explode(',',$goods_skill_image_url);
                $image_urls = array_merge($image_urls,$urls);
            }
            Db::table('goods_skill')->where(['goods_skill_id'=>$ids])->delete();
            // 提交事务
            Db::commit();
            foreach ($ids as $key=>$id){
                // 清空原有库存
                $this->redis->ltrim('goods_skill_'.$id,1,0);
                // 清空抢购者
                $this->redis->ltrim('goods_skill_pp_'.$id,1,0);
            }
            foreach ($image_urls as $key=>$goods_skill_image_url){
                // 当值url经过编码，导致删除失败
                $url = urldecode($goods_skill_image_url);
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
}