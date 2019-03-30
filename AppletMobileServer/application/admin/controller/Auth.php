<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/21
 * Time: 11:08
 */

namespace app\admin\controller;


use think\Controller;

class Auth extends Controller
{
    public function notAuth()
    {
        return $this->fetch();
    }
}