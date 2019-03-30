<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/10
 * Time: 13:42
 */

namespace app\api\controller;


use app\api\logic\IndexLogic;
use app\helps\Tools;
use think\Controller;

class Index extends Controller
{

    private $indexLogic;
    public function __construct()
    {
        parent::__construct();
        $this->indexLogic = new IndexLogic();
    }

    /**
     * 小程序首页数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function indexList()
    {
        $data = $this->indexLogic->indexList();
        Tools::returnJson(200,'获取成功',$data);
    }

}