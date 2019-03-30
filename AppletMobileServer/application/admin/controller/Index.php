<?php
namespace app\admin\controller;

use app\admin\logic\IndexLogic;
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

    public function index()
    {
        $admin_menus = $this->indexLogic->index();
        $this->assign('admin_menus',$admin_menus);
        return $this->fetch();
	}

    /**
     * 后台首页
     * @return mixed
     */
	public function welcome()
    {
        $result = $this->indexLogic->welcome();
        $this->assign('users',$result['users']);
        $this->assign('orders',$result['orders']);
        $this->assign('today_users',$result['today_users']);
        $this->assign('total_users',$result['total_users']);
        $this->assign('today_orders',$result['today_orders']);
        $this->assign('total_orders',$result['total_orders']);
        return $this->fetch();
	}

}
