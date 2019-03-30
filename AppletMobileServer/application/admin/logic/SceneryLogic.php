<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/29
 * Time: 16:58
 */

namespace app\admin\logic;

use app\admin\model\SceneryModel;
use app\helps\Tools;
use think\Controller;
use think\Db;

class SceneryLogic extends Controller
{
    /**
     * 风景列表页
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function index($start,$end)
    {
        if($start && $end)
        {
            $scenerys = SceneryModel::where('updated_at','between',"$start,$end")->order(['updated_at'=>'desc'])->paginate('10');
        }elseif ($start){
            $scenerys = SceneryModel::where('updated_at','>',"$start")->order(['updated_at'=>'desc'])->paginate('10');
        }elseif($end){
            $scenerys =SceneryModel::where('updated_at','<',"$end")->order(['updated_at'=>'desc'])->paginate('10');
        }else{
            $scenerys = SceneryModel::order(['updated_at'=>'desc'])->paginate('10');
        }
        $pages = $scenerys;
        $scenerys = $scenerys->all();
        foreach ($scenerys as $key=>$value){
            $scenerys[$key]['scenery_cover'] = Tools::cutImg2CdnImg($value['scenery_cover'],100);
        }
        return ['pages'=>$pages,'scenerys'=>$scenerys];
    }

    /**
     * 添加数据
     * @return bool
     */
    public function add($form)
    {
        !$form && Tools::returnJson(404,'提交数据出错');
        $title = $form['title'];
        // base64封面
        $cover = $form['cover'];
        $state = $form['state'];
        $address = $form['address'];
        // 经纬度
        $latlng = $form['latlng'];
        //转义html标签
        $content = htmlspecialchars($form['content']);
        //获取图片后缀名
        $ext = Tools::getBase64ImageExt($cover);
        $file_path = 'scenery/cover/'.date('Ymd').'/'.md5(microtime()).'.'.$ext;
        $file_dir = config('root').'/uploads/scenery/cover/'.date('Ymd'.'/');
        !file_exists($file_dir) && mkdir($file_dir,0755,true);
        $local_path = Tools::base64decodeImage($cover,'/uploads/'.$file_path);
        !$local_path && Tools::returnJson(500,'封面图片上传失败');
        $reslut = Tools::cosUploadImg($file_path,$local_path);
        $reslut['code'] === 500 && Tools::returnJson(500,'封面图片上传失败');
        $data = [
            'scenery_title' => $title,
            'scenery_cover' =>urldecode($reslut['url']),
            'scenery_content' => $content,
            'scenery_state' => $state,
            'scenery_address' => $address,
            'scenery_latlng' => $latlng,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        $scenery = SceneryModel::create($data);
        !$scenery && Tools::returnJson(500,'添加数据失败');
        return true;
    }

    /**
     * 修改风景
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update($id,$form)
    {
        if ($this->request->isPost())
        {
            $scenery_id = $id;
            !$scenery_id && Tools::returnJson(404,'参数错误');
            !$form && Tools::returnJson(404,'数据提交出错');
            $scenery = SceneryModel::get($scenery_id);

            $title = $form['title'];
            // base64封面
            $cover = $form['cover'];
            $state = $form['state'];
            $address = $form['address'];
            // 经纬度
            $latlng = $form['latlng'];
            //转义html标签
            $content = htmlspecialchars($form['content']);

            if($cover != $scenery->scenery_cover){
                // 删除cos图片
                Tools::cosDeleteImg($scenery->scenery_cover);
                $local_path = Tools::cosUrl2localUrl($scenery->scenery_cover);
                // 删除服务器本地图片
                @unlink($local_path);
                $ext = Tools::getBase64ImageExt($cover);
                $file_path = 'scenery/cover/'.date('Ymd').'/'.md5(microtime()).'.'.$ext;
                $file_dir = config('root').'/uploads/scenery/cover/'.date('Ymd'.'/');
                !file_exists($file_dir) && mkdir($file_dir,0755,true);
                $local_path = Tools::base64decodeImage($cover,'/uploads/'.$file_path);
                !$local_path && Tools::returnJson(500,'封面图片上传失败');
                $reslut = Tools::cosUploadImg($file_path,$local_path);
                $reslut['code'] === 500 && Tools::returnJson(500,'封面图片上传失败');
                $cover = urldecode($reslut['url']);
            }
            //保存数据
            $scenery->scenery_title = $title;
            $scenery->scenery_cover = $cover;
            $scenery->scenery_content = $content;
            $scenery->scenery_state = $state;
            $scenery->scenery_address = $address;
            $scenery->scenery_latlng = $latlng;
            $scenery->updated_at = time();
            $scenery = $scenery->save();
            !$scenery && Tools::returnJson(500,'添加数据失败');
            return true;
        }else{
            $scenery_id = $id;
            !$scenery_id && Tools::returnJson(404,'参数错误');
            $scenery = SceneryModel::where('scenery_id',$scenery_id)->find();
            return $scenery;
        }
    }

    /**
     * 修改发布状态
     */
    public function state($id,$state)
    {
        if($id && $state !== ''){
            $result = SceneryModel::where('scenery_id',$id)->update(['scenery_state'=>$state,'updated_at'=>time()],true);
            $data = ['state'=>$state === 2 ? '已发布' : '未发布'];
            !$result && Tools::returnJson(400,'更改状态失败');
            return $data;
        }else{
            Tools::returnJson(500,'参数有误');
        }

    }

    /**
     * 删除风景
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete($ids)
    {
        !$ids && Tools::returnJson(404,'参数错误');
        $ids = explode(',',$ids);
        $scenerys = Db::table('scenery')->where('scenery_id','in',$ids)->select();
        foreach ($scenerys as $key=>$scenery){
            Tools::cosDeleteImg($scenery['scenery_cover']);
            @unlink(Tools::cosUrl2localUrl($scenery['scenery_cover']));
        }
        $count = SceneryModel::destroy($ids);
        !$count && Tools::returnJson(500,'删除失败');
        Tools::returnJson(200,'删除成功');
    }
}