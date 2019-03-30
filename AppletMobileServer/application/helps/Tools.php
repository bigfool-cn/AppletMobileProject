<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/20
 * Time: 16:20
 */

namespace app\helps;

use Qcloud\Cos\Client;
use think\Exception;
use WxBizDataCrypt\wxBizDataCrypt;

class Tools
{
    /**
     * 返回josn数据
     * @param $code
     * @param $msg
     * @param array $data
     */
    public static function returnJson($code,$msg,$data=array())
    {
        $datas = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        echo json_encode($datas,true);
        exit();
    }

    /**
     * curl get/post请求
     * @param $url
     * @param $method
     * @param array $data
     * @return mixed
     */
    public static function curl($url,$method,$data=array())
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息不作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if($method === 'post'){
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            //设置post数据
            $post_data = $data;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        }
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }

    /**
     * 解密微信用户信息
     * @param $sessionKey
     * @param $encryptedData
     * @param $iv
     */
    public static function decryptWxData($sessionKey,$encryptedData,$iv)
    {
        $appid = config('wx.AppID');
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData,$iv,$data);
        if ($errCode == 0) {
            return $data;
        } else {
            return $errCode;
        }
    }

    /**
     * 加密数据
     * @param array $data 原始数组数据
     * @return string 加密后字符串密文
     */
    public static function enctyptData($data=array())
    {

        $key=config('wx.AppSecret');

        $iv=substr(config('wx.AppSecret'),0,16);

        $data = json_encode($data,true);

        $result=openssl_encrypt($data, 'AES-128-CBC',$key,0,$iv);

        $result=base64_encode($result);

        return $result;
    }

    /**
     * 解密数据
     * @param $data 加密的数据字符串
     * @return int|string
     */
    public static function decryptData($data)
    {
        $key=config('wx.AppSecret');

        $iv=substr(config('wx.AppSecret'),0,16);

        $data = base64_decode($data);

        $result = openssl_decrypt($data, 'AES-128-CBC', $key, 0, $iv);

        if($result){

            return $result;

        }else{

            return 0;

        }
    }

    /**
     * 腾讯云cos上传图片
     * @param $file_path cos文件路径名
     * @param $local_path 本地文件路径名
     * @return array
     */
    public static function cosUploadImg($file_path,$local_path)
    {
        $cosClient = new Client(array(
            'region' => config('wx.region'),
            'credentials' => config('wx.credentials')
        ));
        $bucket = config('wx.bucket');
        $key = $file_path;
        try {
            $result = $cosClient->putObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => fopen($local_path, 'rb')
            ));
            $url = preg_replace('/'.config('wx.cos_baseurl2').'/',config('wx.cdn_baseurl'),$result['ObjectURL']);
            return ['code'=>200,'url'=>$url];
        } catch (\Exception $e) {
            return ['code'=>500,'url'=>''];
        }
    }

    /**
     * 腾讯云cos删除图片
     * @param $file_path
     * @return bool
     */
    public static function cosDeleteImg($file_path)
    {
        //替换掉域名
        $file_path = preg_replace('/'.config('wx.cdn_baseurl2').'/','',$file_path);
        $cosClient = new Client(array(
            'region' => config('wx.region'),
            'credentials' => config('wx.credentials')
        ));
        // 删除 COS 对象
        $result = $cosClient->deleteObject(array(
            //bucket 的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
            'Bucket' => config('wx.bucket'),
            'Key' => $file_path));
        return true;
    }

    /**
     * 获取base64图片后缀名
     * @param $base64
     * @return mixed
     */
    public static function getBase64ImageExt($base64)
    {
        try{
            preg_match('/^data:\s*image\/(\w+);base64,/',$base64,$base_info);
            return $base_info[1];
        }catch (Exception $e){
           Tools::returnJson(500,'图片格式不正确');
        }
    }
    /**
     * 解析base64图片
     * @param $base64 base64元数据
     * @param $file_path 文件路径
     * @return bool
     */
    public static function base64decodeImage($base64,$file_path)
    {
        $file_path = config('root').$file_path;
        // 判断文件是否存在，不存在则创建
        if(!file_exists($file_path)){
            $f = fopen($file_path,'w');
            fclose($f);
        };
        $base64_string= explode(',', $base64);
        $data = base64_decode($base64_string[1]);
        $bytes = file_put_contents($file_path,$data);
       if(!$bytes){
           return false;
       }else{
           return $file_path;
       }
    }

    /**
     * cos路径转服务器本地路径
     * @param $cos_url cos文件路径
     * @return string
     */
    public static function cosUrl2localUrl($cos_url)
    {
        if(preg_match('/^'.config('wx.cdn_imgbaseurl').'/',$cos_url)){
            // 去掉尾部参数
            $cos_url = preg_match('/\d+$/','',$cos_url);
            $file_path = preg_replace('/^'.config('wx.cdn_imgbaseurl').'/','',$cos_url);
        }else{
            $file_path = preg_replace('/^'.config('wx.cdn_baseurl2').'/','',$cos_url);
        }
        $local_url = config('root').'/uploads/'.$file_path;
        return $local_url;
    }

    /**
     * 剪切cos图片并转化为数据万象地址
     * @param $cos_url cos图片地址
     * @param $size 图片大小
     * @return null|string|string[] 数据万象图片地址
     */
    public static function cutImg2CdnImg($cos_url,$size)
    {
        $file_path = preg_replace('/'.config('wx.cdn_baseurl2').'/',config('wx.cdn_imgbaseurl'),$cos_url);
        $file_path = $file_path.'/'.$size;
        return $file_path;
    }

    /**
     * 获取指定日期之前的几天内的日期
     * @param string $start_date
     * @param int $count
     * @return array
     */
    public static function getNDate($count=7,$start_date='')
    {
        $start_time = $start_date?strtotime($start_date):strtotime(date('Y-m-d'));
        $data = array();
        for($i = $count-1; $i >= 0; $i--){
            $current_time = $start_time - 60*60*24*$i;
            array_push($data,date('Y-m-d',$current_time));
        }
        return $data;
    }
}