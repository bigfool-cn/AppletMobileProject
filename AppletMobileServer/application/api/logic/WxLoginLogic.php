<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/22
 * Time: 10:01
 */

namespace app\api\logic;

use app\helps\Tools;
use think\Controller;
use think\Db;

class WxLoginLogic extends Controller
{

    /**
     * 用户授权登录
     */
    public function wxLogin()
    {
        $code = $this->request->post('code');
        $encryptedData = $this->request->post('encryptedData');
        $iv = $this->request->post('iv');
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.config('wx.AppID').'&secret='.config('wx.AppSecret').'&js_code='.$code.'&grant_type=authorization_code';
        $data = Tools::curl($url,'get');
        $data = json_decode($data,true);
        $datas = Tools::decryptWxData($data['session_key'],$encryptedData,$iv);
        $datas = json_decode($datas,true);
        $user_id = Db::table('user')->where('user_openid',$datas['openId'])->value('user_id');
        $api_token = md5(uniqid(mt_rand(), true).'bigfool.cn');
        Db::table('user')->where('user_openid',$datas['openId'])
            ->update(['user_name'=>$datas['nickName'],'user_avatar'=>$datas['avatarUrl'],'api_token'=>$api_token,'login_time'=>time()]);
        //判断用户是否存在，不存在则加入用户表
        if($user_id){
            //用户登录日志
            $data = [
                'user_id' => $user_id,
                'user_name' => $datas['nickName'],
                'login_time' => time(),
            ];
            Db::table('user_login_log')->insert($data);
           return['code'=>200,'msg'=>'登录成功','data'=>array('api_token'=>$api_token,'user_id'=>$user_id)];
        }else{
            $data = [
                'user_openid' => $datas['openId'],
                'user_name' => $datas['nickName'],
                'user_avatar' => $datas['avatarUrl'],
                'user_gender' => $datas['gender'],
                'user_address' => $datas['country'].'-'.$datas['province'].'-'.$datas['city'],
                'api_token' => $api_token,
                'login_time' => time()
            ];
            $user_id = Db::table('user')->insertGetId($data);
            if(!$user_id){
                return ['code'=>400,'msg'=>'用户创建失败'];
            }
            return ['code'=>200,'msg'=>'登录成功','data'=>array('api_token'=>$api_token,'user_id'=>$user_id)];
        }
    }
}