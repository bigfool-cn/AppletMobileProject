<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/19
 * Time: 20:30
 */

namespace app\admin\logic;


use think\Db;
use think\facade\Cookie;

class UserRoleLogic
{
    /**
     * 用户角色列表
     * @return array
     */
    public function index()
    {
        $userRoles = Db::table('user_role')->alias('a')
            ->leftJoin('admin_user b','a.admin_user_id=b.admin_user_id')
            ->leftJoin('role c','a.role_id=c.role_id')
            ->field('a.user_role_id,a.admin_user_id,a.role_id,b.admin_user_name,c.role_name,a.create_time,a.update_time')
            ->paginate('15');
        $userRolesAll = $userRoles->all();
        // 屏蔽自己和超级管理员
        $exclude = array(1,Cookie::get('user_id'));
        return ['exclude'=>$exclude,'userRolesAll'=>$userRolesAll,'pages'=>$userRoles];
    }

    /**
     * 添加用户角色
     * @param array $form
     * @param string $method
     * @return array
     */
    public function add($form=array(),$method='GET')
    {
        if($method == 'POST'){
            $form['create_time'] = time();
            $form['update_time'] = time();
            $res = Db::table('user_role')->insert($form);
            if ($res){
                return ['code'=>200,'msg'=>'添加成功'];
            }else{
                return ['code'=>500,'msg'=>'添加失败'];
            }
        }else{
            // 屏蔽已有角色用户和超级管理员角色
            $admin_user_ids = Db::table('user_role')->column('admin_user_id');
            $adminUsers = Db::table('admin_user')->where('admin_user_id','not in', $admin_user_ids)
                ->field('admin_user_id,admin_user_name')->select();
            $roles = Db::table('role')->where('role_id','<>',1)
                ->field('role_id,role_name')->select();
            return ['adminUsers'=>$adminUsers,'roles'=>$roles];
        }
    }

    /**
     * 修改用户角色
     * @param $id
     * @param array $form
     * @param string $method
     * @return array
     */
    public function update($id,$form=array(),$method='GET')
    {
        if($method == 'POST'){
            $form['update_time'] = time();
            $res = Db::table('user_role')->where(['user_role_id'=>$id])->update($form);
            if ($res){
                return ['code'=>200,'msg'=>'修改成功'];
            }else{
                return ['code'=>500,'msg'=>'修改失败'];
            }
        }else{
            // 屏蔽超级管理员角色
            $user_role = Db::table('user_role')->alias('a')
                ->leftJoin('admin_user b','a.admin_user_id=b.admin_user_id')
                ->where(['user_role_id'=>$id])
                ->field('user_role_id,a.admin_user_id,role_id,b.admin_user_name')->find();
            $roles = Db::table('role')->where('role_id','<>',1)
                ->field('role_id,role_name')->select();
            return ['user_role'=>$user_role,'roles'=>$roles];
        }
    }

    /**
     * 删除用户角色
     * @param $ids
     * @return array
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        $user_id = Cookie::get('user_id');
        $exclude = [$user_id,1];
        // 排除自己和超级管理员
        Db::table('user_role')->where(['user_role_id'=>$ids])->where('admin_user_id','not in',$exclude)->delete();
        return ['code'=>200,'msg'=>'删除成功'];
    }
}