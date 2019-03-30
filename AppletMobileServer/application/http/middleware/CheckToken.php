<?php

namespace app\http\middleware;

use app\helps\Tools;
use think\Db;

class CheckToken
{
    public function handle($request, \Closure $next)
    {
        $api_token = $request->header()['token'];
        !$api_token && Tools::returnJson(4001,'无效签名');
        $count = Db::table('user')->where(['api_token'=>$api_token])->count();
        if(!$count){
            Tools::returnJson(4001,'无效签名');
        }
        return $next($request);
    }
}
