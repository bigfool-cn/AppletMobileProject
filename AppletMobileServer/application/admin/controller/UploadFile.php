<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/2/19
 * Time: 13:38
 */

namespace app\admin\controller;


use app\helps\Tools;
use think\Controller;

class UploadFile extends Controller
{
    /**
     * 上传图片到腾讯云cos
     */
    public function uploadImage()
    {
        $file = $this->request->file('file');
        $type = $this->request->post('type');
        // 保存在本地
        $info = $file->move( '../uploads/'.$type);
        if($info){
            // cos路径
            $file_path = $type.'/images/'.date('Ymd').'/'.md5(microtime()).'.'.$info->getExtension();
            // 本地文件路径
            $local_path = config('root').'/uploads/'.$type.'/'.$info->getSaveName();
            $reslut = Tools::cosUploadImg($file_path,$local_path);
            $reslut['code'] === 500 && Tools::returnJson(500,'图片上传失败');
            // 上传成功后删除本地文件
            @unlink($local_path);
            Tools::returnJson(200,'上传成功',['url'=>$reslut['url']]);
        }else{
            // 上传失败获取错误信息
            Tools::returnJson(500,'上传失败');
        }
    }

    /**
     * 上传ueditor图片到腾讯云cos
     */
    public function ueditorUploadImage()
    {
        $file = $this->request->file('upfile');
        $type = "goods_detail";
        // 保存在本地
        $info = $file->move( '../uploads/'.$type);
        if($info){
            $fileName = md5(microtime()).'.'.$info->getExtension();
            // cos路径
            $file_path = $type.'/images/'.date('Ymd').'/'.$fileName;
            // 本地文件路径
            $local_path = config('root').'/uploads/'.$type.'/'.$info->getSaveName();
            $reslut = Tools::cosUploadImg($file_path,$local_path);
            $reslut['code'] === 500 && Tools::returnJson(500,'图片上传失败');
            $res = array(
                "state"    => $reslut['code']==200 ? 'SUCCESS': '上传失败', //上传状态，上传成功时必须返回"SUCCESS"
                "url"      => $reslut['url'], //CDN地址
                "title"    => $fileName, //新文件名
                "original" => $info->getFilename(), //原始文件名
                "type"     => '.'.$info->getExtension(), //文件类型
                "size"     => $info->getSize(), //文件大小
            );
            // 上传成功后删除本地文件
            @unlink($local_path);
            echo json_encode($res);
            die;
        }else{
            // 上传失败获取错误信息
            $res = array(
                "state"    => '上传失败', //上传状态，上传成功时必须返回"SUCCESS"
                "url"      => '', //CDN地址
                "title"    => $file['filename'], //新文件名
                "original" => $file['filename'], //原始文件名
                "type"     => $file['info']['type'], //文件类型
                "size"     => $file['info']['size'], //文件大小
            );
            echo json_encode($res);
            die;
        }
    }

    /**
     * 删除腾讯云cos图片
     */
    public function delImage()
    {
        $url = $this->request->post('img_url','');
        // 防止url经过编码造成删除失败;
        $url = urldecode($url);
        !$url && Tools::returnJson(401,'地址不存在');
        Tools::cosDeleteImg($url);
        Tools::returnJson(200,'删除成功');
    }

}