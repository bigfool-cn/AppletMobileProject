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

class RoleLogic
{
    /**
     * 角色列表
     * @return array
     */
    public function index()
    {
        $roles = Db::table('role')->paginate(15);
        $rolesAll = $roles->all();
        $role_id = Db::table('user_role')->where(['admin_user_id'=>Cookie::get('user_id')])
            ->value('role_id');
        // 屏蔽自己和超级管理员
        $exclude = array(1,$role_id);
        return ['exclude'=>$exclude,'roles' => $rolesAll, 'pages' => $roles];
    }

    /**
     * 添加角色
     * @param string $method 请求方法
     * @param array $form 数据
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function add($method = 'GET', $form = array())
    {
        if($method == 'POST'){
            if(!$form['role_name']){
                return ['code' => 400, 'msg' => '角色名称为空'];
            }
            $data = array(
                'role_name' => $form['role_name'],
                'permissions' => json_encode(explode(',',$form['permissions'])),
                'create_time' => time(),
                'update_time' => time()
            );
            $res =  Db::table('role')->insert($data);
            if ($res){
                return ['code' => 200, 'msg' => '添加成功'];
            }else{
                return ['code' => 500, 'msg' => '添加失败'];
            }
        }else{
            $permissions = Db::table('permission')->field('permission_id,permission_name,method')->select();
            return $permissions;
        }
    }

    /**
     * 修改角色
     * @param $id role_id
     * @param string $method 请求方法
     * @param array $form 数据
     * @return array
     */
    public function update($id,$method = 'GET', $form = array())
    {
        if($method == 'POST'){
            if(!$form['role_name']){
                return ['code' => 400, 'msg' => '角色名称为空'];
            }
            $data = array(
                'role_name' => $form['role_name'],
                'permissions' => json_encode(explode(',',$form['permissions'])),
                'create_time' => time(),
                'update_time' => time()
            );
            $res =  Db::table('role')->where(['role_id'=>$id])->update($data);
            if ($res){
                return ['code' => 200, 'msg' => '修改成功'];
            }else{
                return ['code' => 500, 'msg' => '修改失败'];
            }
        }else{
            $role = Db::table('role')->where(['role_id'=>$id])
                ->field('role_id,role_name,permissions')->find();
            $role['permissions'] = json_decode($role['permissions'],true);
            $permissions = Db::table('permission')
                ->field('permission_id,permission_name,method')->select();
            return ['role'=>$role,'permissions'=>$permissions];
        }
    }

    /**
     * 删除角色
     * @param $ids
     * @return array
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        // 排除超级管理员
        $ids = array_diff($ids,[1]);
        Db::table('role')->where(['role_id'=>$ids])->delete();
        return ['code'=>200,'msg'=>'删除成功'];
    }
}