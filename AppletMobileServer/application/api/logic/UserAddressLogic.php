<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/7
 * Time: 16:49
 */

namespace app\api\logic;


use app\admin\logic\UserLoginLogLogic;
use app\api\validate\UserAddressValidate;
use think\Db;

class UserAddressLogic
{
    private $userAddressLogic,$userAddressValidate;
    public function __construct()
    {
        $this->userAddressLogic = new UserLoginLogLogic();
        $this->userAddressValidate = new UserAddressValidate();
    }

    /**
     * 用户收货地址列表
     * @param $user_id 用户id
     */
    public function index($user_id)
    {
        $address = Db::table('user_address')->where(['user_id'=>$user_id])
            ->field('address_id,addressee,mobile,address,xx_address,is_mr')->select();
        return $address;

    }
    /**
     * 添加收货地址
     * @param $form 提交数据数组
     * @return array
     */
    public function add($form)
    {
        if($this->userAddressValidate->check($form)){
            $data = array(
                'user_id'  =>  $form['user_id'],
                'addressee' => $form['addressee'],
                'mobile' => $form['mobile'],
                'address' => $form['address'],
                'xx_address' => $form['xx_address'],
                'address_index' => $form['address_index'],
                'is_mr' => intval($form['mr']),
                'create_time' => time(),
                'update_time' => time()
            );
            Db::startTrans();
            try{
                $address_id = Db::table('user_address')->insertGetId($data);
                // 更新是否默认地址
                if($form['mr'] == 'true'){
                    Db::table('user_address')->where(['user_id'=>$form['user_id']])->update(['is_mr'=>0]);
                    Db::table('user_address')->where(['address_id'=>$address_id])->update(['is_mr'=>1]);
                }
                Db::commit();
                return ['code'=>200,'msg'=>'添加成功'];
            }catch (\Exception $e){
                // 回滚事物
                Db::rollback();
                return ['code'=>500,'msg'=>'添加失败'];
            }
        }else{
            return ['code'=>401,'msg'=>$this->userAddressValidate->getError()];
        }
    }


    public function edit($address_id,$user_id,$method="GET",$form=array())
    {
        if($method == "POST"){
            $data = array(
                'addressee' => $form['addressee'],
                'mobile' => $form['mobile'],
                'address' => $form['address'],
                'xx_address' => $form['xx_address'],
                'address_index' => $form['address_index'],
                'is_mr' => intval($form['mr']),
                'update_time' => time()
            );
            Db::startTrans();
            try{
                Db::table('user_address')->where(['address_id'=>$address_id])->update($data);
                // 更新是否默认地址
                if($form['mr'] == 'true'){
                    Db::table('user_address')->where(['user_id'=>$user_id])->update(['is_mr'=>0]);
                    Db::table('user_address')->where(['address_id'=>$address_id])->update(['is_mr'=>1]);
                }
                Db::commit();
                return ['code'=>200,'msg'=>'修改成功','data'=>array()];
            }catch (\Exception $e){
                // 回滚事物
                Db::rollback();
                return ['code'=>500,'msg'=>'修改失败','data'=>array()];
            }
        }else{
            $address = Db::table('user_address')->where(['address_id'=>$address_id])
                ->field('address_id,addressee,mobile,address,xx_address,is_mr,address_index')->find();
            return ['code'=>200,'msg'=>'ok','data'=>$address];
        }
    }
    /**
     * 删除收货地址
     * @param $address_id
     * @param $user_id
     * @return array
     */
    public function delete($address_id,$user_id)
    {
        try{
            $is_mr = Db::table('user_address')->where(['address_id'=>$address_id])->value('is_mr');
            if($is_mr){
                // 如果删除的地址是默认地址，重新选择一个地址作为默认地址
                $addressIds = Db::table('user_address')->where(['user_id'=>$user_id])->column('address_id');
                $addressIds && Db::table('user_address')->where(['address_id'=>$addressIds[0]])->update(['is_mr'=>1]);
            }
            Db::table('user_address')->where(['address_id'=>$address_id])->delete();
            $address = Db::table('user_address')->where(['user_id'=>$user_id])
                ->field('address_id,addressee,mobile,address,xx_address,is_mr')->select();
            return ['code'=>200,'msg'=>'删除成功','data'=>$address];
        }catch (\Exception $e){
            return ['code'=>500,'msg'=>'删除失败','data'=>array()];
        }
    }

    /**
     * 修改默认地址
     * @param $address_id
     * @param $user_id 用户id
     * @return array
     */
    public function editMr($address_id,$user_id)
    {
        Db::startTrans();
        try{
            Db::table('user_address')->where(['user_id'=>$user_id])->update(['is_mr'=>0]);
            Db::table('user_address')->where(['address_id'=>$address_id])->update(['is_mr'=>1]);
            Db::commit();
            $address = Db::table('user_address')->where(['user_id'=>$user_id])
                ->field('address_id,addressee,mobile,address,xx_address,is_mr')->select();
            return ['code'=>200,'msg'=>'修改成功','data'=>$address];
        }catch (\Exception $e){
            Db::rollback();
            return ['code'=>500,'msg'=>'修改失败','data'=>array()];
        }
    }

    /**
     * 获取默认地址
     * @param $user_id
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function getMrAddress($user_id)
    {
        $address = Db::table('user_address')->where(['user_id'=>$user_id])
            ->field('address_id,addressee,mobile,address,xx_address')->find();
        return $address;
    }
}