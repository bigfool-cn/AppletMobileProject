<?php

namespace app\http\middleware;

use think\facade\Cache;
use think\facade\Cookie;

class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        $key = Cookie::get('user');
        if(Cache::store('redis')->get($key)){
            return $next($request);
        }else{
            return redirect(url('/login'));
            //header('parent.location.href='.url('/login'));
        }
    }
}
