<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/22
 * Time: 14:38
 */

namespace app\admin\logic;


use app\admin\validate\SlideShowValidate;
use app\helps\Tools;
use think\Db;

class SlideShowLogic
{
    private $goodsCates,$slideShowValidate; // 商品分类,商品
    public function __construct()
    {
        $this->goodsCates = Db::table('goods_cate')->field('goods_cate_id,goods_cate_name')->select();
        $this->slideShowValidate = new SlideShowValidate();
    }

    /**
     * 商城首页轮播图列表
     * @return array
     */
    public function index()
    {
        $slideShows = Db::table('slide_show')->alias('a')
            ->leftJoin('goods b','a.goods_id=b.goods_id')
            ->order(['a.update_time'=>'desc'])
            ->field('a.slide_id,a.image_url,a.sort,a.create_time,a.update_time,b.goods_name')
            ->paginate('15');
        $slideShowsAll = $slideShows->all();
        $sortArr = array_column($slideShowsAll,'sort');
        // 获取当前最大排序值
        $maxSort =  $sortArr ? max($sortArr) : 0;
        foreach ($slideShowsAll as $key=>$slideShow){
            //使用腾讯云数据万象进行图片裁剪
            $slideShowsAll[$key]['image_url_320'] = Tools::cutImg2CdnImg($slideShow['image_url'],320);
        }
        return ['slideShows'=>$slideShowsAll,'pages'=>$slideShows,'maxSort'=>$maxSort];
    }

    /**
     * 添加
     * @param array $form 表单数据
     * @param string $method 请求方法
     * @return array
     */
    public function add($form=array(),$method="GET")
    {
        if($method == "POST"){
            if($this->slideShowValidate->check($form)){
                $data = array(
                    'goods_id'=>$form['goods_id'],
                    'image_url'=>urldecode($form['image_url']),
                    'create_time'=>time(),
                    'update_time'=>time()
                );
                $num = Db::table('slide_show')->insert($data);
                if($num){
                    return ['code'=>200,'msg'=>'添加成功'];
                }else{
                    return ['code'=>500,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>401,$this->slideShowValidate->getError()];
            }
        }else{
            $goods = Db::table('goods')->where(['goods_cate_id'=>$this->goodsCates[0]['goods_cate_id']])
                ->field('goods_id,goods_name')->select();
            return ['goodsCates'=>$this->goodsCates,'goods'=>$goods];
        }
    }

    public function update($id,$form=array(),$method='GET')
    {
        if ($method == 'POST'){
            if($this->slideShowValidate->check($form)){
                $data = array(
                    'goods_id'=>$form['goods_id'],
                    'image_url'=>urldecode($form['image_url']),
                    'update_time'=>time()
                );
                $num = Db::table('slide_show')->where(['slide_id'=>$id])->update($data);
                if($num){
                    return ['code'=>200,'msg'=>'修改成功'];
                }else{
                    return ['code'=>500,'msg'=>'修改失败'];
                }
            }else{
                return ['code'=>401,$this->slideShowValidate->getError()];
            }
        }else{
            $slideShow = Db::table('slide_show')->alias('a')
                ->leftJoin('goods b','a.goods_id=b.goods_id')
                ->where(['a.slide_id'=>$id])
                ->field('a.slide_id,a.image_url,a.goods_id,b.goods_cate_id')
                ->find();
            $goods = Db::table('goods')->where(['goods_cate_id'=>$slideShow['goods_cate_id']])
                ->field('goods_id,goods_name')->select();
            return ['slideShow'=>$slideShow,'goodsCates'=>$this->goodsCates,'goods'=>$goods];
        }
    }

    /**
     * 删除首页轮播图
     * @param $ids
     * @return array
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        try {
            // 获取轮播图地址
            $image_urls = Db::table('slide_show')->where(['slide_id'=>$ids])->column('image_url');
            $num = Db::table('slide_show')->where(['slide_id'=>$ids])->delete();
            if($num){
                foreach ($image_urls as $key=>$image_url){
                    // 当值url经过编码，导致删除失败
                    $url = urldecode($image_url);
                    // 删除腾讯云cos上的图片
                    Tools::cosDeleteImg($url);
                }
                return ['code'=>200,'msg'=>'删除成功'];
            }else{
                return ['code'=>500,'msg'=>'删除失败'];
            }
        } catch (\Exception $e) {
            return ['code'=>500,'msg'=>'删除失败'];
        }
    }

    /**
     * 更新排序
     * @param $id slide_id
     * @param $sort 排序值
     * @return array
     */
    public function sort($id,$sort)
    {
        $res = Db::table('slide_show')->where(['slide_id'=>$id])->update(['sort'=>$sort,'update_time'=>time()]);
        if($res){
            return ['code'=>200,'msg'=>'更新排序成功'];
        }else{
            return ['code'=>500,'msg'=>'更新排序失败'];
        }
    }

}