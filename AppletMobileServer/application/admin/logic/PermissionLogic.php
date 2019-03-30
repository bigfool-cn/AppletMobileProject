<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/19
 * Time: 20:30
 */

namespace app\admin\logic;


use app\admin\validate\PermissionValidate;
use think\Db;

class PermissionLogic
{

    private $permissionValidate;
    public function __construct()
    {
        $this->permissionValidate = new PermissionValidate();
    }

    /**
     * 权限列表
     * @return array
     */
    public function index()
    {
        $permissions = Db::table('permission')->paginate(15);
        $permissionsAll = $permissions->all();
        return ['permissionsAll'=>$permissionsAll,'pages'=>$permissions];
    }

    /**
     * 添加权限
     * @param $form
     * @return array
     */
    public function add($form)
    {
        if($this->permissionValidate->check($form)){
            $form['create_time'] = time();
            try{
                Db::startTrans();
                $permission_id = Db::table('permission')->insertGetId($form);
                // 为超级管理员添加权限
                $permissions = Db::table('role')->where(['role_id'=>1])->value('permissions');
                $permissions = json_decode($permissions,true);
                array_push($permissions,$permission_id);
                $permissions = json_encode($permissions);
                Db::table('role')->where(['role_id'=>1])->update(['permissions'=>$permissions]);
                Db::commit();
                return ['code'=>200,'msg'=>'添加成功'];
            }catch (\Exception $e){
                Db::rollback();
                return ['code'=>500,'msg'=>'添加失败'];
            }
        }else{
            return ['code'=>400,'msg'=>$this->permissionValidate->getError()];
        }
    }

    /**
     * 删除权限
     * @param $ids
     * @return array
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        try{
            Db::startTrans();
            Db::table('permission')->where(['permission_id'=>$ids])->delete();
            $permissions = Db::table('role')->field('role_id,permissions')->select();
            // 更新所有角色的权限
            foreach ($permissions as $key=>$value){
                $old_permissions = json_decode($value['permissions'],true);
                $new_permissions = array_diff($old_permissions,$ids);
                if($old_permissions == $new_permissions) continue;
                Db::table('role')->where(['role_id'=>$value['role_id']])->update(['permissions'=>json_encode($new_permissions)]);
            }
            Db::commit();
            return ['code'=>200,'msg'=>'删除成功'];
        }catch (\Exception $e){
            Db::rollback();
            return ['code'=>500,'msg'=>'删除失败'];
        }
    }
}