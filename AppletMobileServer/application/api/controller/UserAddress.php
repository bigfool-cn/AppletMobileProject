<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/7
 * Time: 16:48
 */

namespace app\api\controller;


use app\api\logic\UserAddressLogic;
use app\helps\Tools;
use think\Controller;

class UserAddress extends Controller
{
    private $userAddressLogic;
    public function __construct()
    {
        parent::__construct();
        $this->userAddressLogic = new UserAddressLogic();
    }

    /**
     * 用户收货地址列表
     * @param int $user_id 用户id
     */
    public function index($user_id=0)
    {
        !$user_id && Tools::returnJson(401,'参数错误');
        $res = $this->userAddressLogic->index($user_id);
        Tools::returnJson(200,'ok',$res);
    }

    /**
     * 添加收货地址
     */
    public function add()
    {
        $form = $this->request->post();
        !$form && Tools::returnJson(401,'提交数据为空');
        $res = $this->userAddressLogic->add($form);
        Tools::returnJson($res['code'],$res['msg']);
    }

    public function edit($address_id,$user_id)
    {
        (!$address_id || !$user_id) && Tools::returnJson(401,'参数错误');
        if($this->request->isPost()){
            $form = $this->request->post();
            !$form && Tools::returnJson(401,'提交数据为空');
            $res = $this->userAddressLogic->edit($address_id,$user_id,'POST',$form);
            Tools::returnJson($res['code'],$res['msg'],$res['data']);
        }else{
            $res = $this->userAddressLogic->edit($address_id,$user_id);
            Tools::returnJson($res['code'],$res['msg'],$res['data']);
        }
    }

    /**
     * 删除收货地址
     * @param int $address_id
     * @param int $user_id 用户id
     */
    public function delete($address_id=0,$user_id=0)
    {
        (!$address_id || !$user_id) && Tools::returnJson(401,'参数错误');
        $res = $this->userAddressLogic->delete($address_id,$user_id);
        Tools::returnJson($res['code'],$res['msg'],$res['data']);
    }

    /**
     * 修改地址默认选项
     * @param int $address_id
     * @param int $user_id
     */
    public function editMr($address_id=0,$user_id=0)
    {
        (!$address_id || !$user_id) && Tools::returnJson(401,'参数错误');
        $res = $this->userAddressLogic->editMr($address_id,$user_id);
        Tools::returnJson($res['code'],$res['msg'],$res['data']);
    }

    public function getMrAddress($user_id=0)
    {
        !$user_id && Tools::returnJson(401,'参数错误');
        $address = $this->userAddressLogic->getMrAddress($user_id);
        Tools::returnJson(200,'ok',$address);
    }
}