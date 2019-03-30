<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/21
 * Time: 10:31
 */

namespace app\http\middleware;


use think\Db;
use think\facade\Cookie;

class CheckPermission
{
    public function handle($request, \Closure $next)
    {
        $route = $request->pathinfo();
        $method = $request->method();
        $route = preg_replace('/\.html/','',$route);
        $admin_user_id = Cookie::get('user_id');
        $permissions = Db::table('role')->alias('a')
            ->leftJoin('user_role b','a.role_id=b.role_id')
            ->where(['admin_user_id'=>$admin_user_id])
            ->value('permissions');
        $permissions = json_decode($permissions,true);
        $routeMethods = Db::table('permission')->where(['permission_id'=>$permissions])
            ->field('concat(lower(`controller`),"/",lower(`action`)) as route,method')->select();
        foreach ($routeMethods as $key=>$value){
            if(($route==$value['route']) && (strtolower($method) == strtolower($value['method']))){
                return $next($request);
            }
        }
        header('Location:/not-auth');
        return $next($request);
    }
}