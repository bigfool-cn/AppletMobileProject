<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/1/3
 * Time: 11:10
 */

namespace app\admin\logic;


use app\admin\validate\AdminMenuValidate;
use function PHPSTORM_META\elementType;
use think\Controller;
use think\Db;

class AdminMenuLogic extends Controller
{
    private $adminMenuValidate;
    private $rootMenu; //目录
    public function __construct()
    {
        parent::__construct();
        $menus = Db::table('admin_menu')->where(['parent_id'=>0])->field('admin_menu_id,admin_menu_name')->all();
        $data = array((['admin_menu_id'=>0,'admin_menu_name'=>'根目录']));
        $this->rootMenu = array_merge($data,$menus);
        $this->adminMenuValidate = new AdminMenuValidate();
    }

    /**
     * 栏目列表页
     * @return array
     */
    public function index()
    {
        $admin_menus = Db::table('admin_menu')->where(['parent_id'=>0])
            ->order(['sort'=>'desc','parent_id'=>'asc'])->all();
        $menus = array();
        $count = 0;
        foreach($admin_menus as $key=>$value) {
            $menus[$value['admin_menu_id']] = $value;
            $child = Db::table('admin_menu')->where(['parent_id'=>$value['admin_menu_id']])->select();
            $menus[$value['admin_menu_id']]['child'] = $child;
            $count += count($child);
        }
        $total = count($admin_menus) + $count;
        $admin_menus = array_values($menus);
        return ['admin_menus'=>$admin_menus,'total'=>$total];//,'pages'=>$admin_menu
    }

    /**
     * 栏目添加
     * @param array $form 表单数据
     */
    public function add($form=array())
    {
        if($this->request->isPost()){
            if($this->adminMenuValidate->check($form)){
                $data = array_merge($form, array('create_time' => time(), 'update_time' => time()));
                $res = Db::table('admin_menu')->insert($data);
                if($res){
                    return ['code'=>200,'msg'=>'添加成功'];
                }else{
                    return ['code'=>500,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>500,'msg'=>$this->adminMenuValidate->getError()];
            }
        }else{
            $rootMenus = $this->rootMenu;
            return $rootMenus;
        }
    }

    /**
     * 修改后台栏目
     * @param $id 栏目id
     * @param array $form 表单数据
     * @return array
     */
    public function update($id,$form=array())
    {
        if($this->request->isPost()){
            if($this->adminMenuValidate->check($form)){
                $data = array_merge($form, array('update_time' => time()));
                $res = Db::table('admin_menu')->where(['admin_menu_id'=>$id])->update($data);
                if($res){
                    return ['code'=>200,'msg'=>'修改成功'];
                }else{
                    return ['code'=>500,'msg'=>'修改失败'];
                }
            }else{
                return ['code'=>500,'msg'=>$this->adminMenuValidate->getError()];
            }
        }else{
            $menu = Db::table('admin_menu')->where(['admin_menu_id'=>$id])->find();
            return ['rootMenu'=>$this->rootMenu,'menu'=>$menu];
        }
    }

    /**
     * 后台栏目删除
     * @param $ids admin_menu_id 串
     * @return int
     */
    public function delete($ids)
    {
        $ids = explode(',',$ids);
        $num = 0;
        foreach ($ids as $key=>$id){
            $parent_id = Db::table('admin_menu')->where(['admin_menu_id'=>$id])->value('parent_id');
            if ($parent_id == 0){
                $num += Db::table('admin_menu')->where(['admin_menu_id'=>$id])->whereOr(['parent_id'=>$id])->delete();
            }else{
                $num += Db::table('admin_menu')->where(['admin_menu_id'=>$id])->delete();
            }
        }
        return $num;
    }

    /**
     * 排序
     * @param $id admin_menu_id
     * @return int|string
     */
    public function sort($id)
    {
        $admin_menu = Db::table('admin_menu')->where(['admin_menu_id'=>$id])->field('sort')->find();
        $num = Db::table('admin_menu')->where(['admin_menu_id'=>$id])->update(['sort'=>$admin_menu['sort']+1]);
        return $num;
    }
}