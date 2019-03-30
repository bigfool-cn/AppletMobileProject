<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/12/11
 * Time: 14:19
 */

namespace app\admin\logic;


use app\helps\Tools;
use think\Controller;
use think\Db;

class AppletMenuLogic extends Controller
{
    /**
     * 小程序栏目列表
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $applet_menus = Db::table('applet_menu')->order('applet_menu_sort desc')->select();
        return $applet_menus;
    }

    /**
     * 小程序栏目添加
     * @param $data 表单数据
     * @return int|string
     */
    public function add($form)
    {
        !$form && Tools::returnJson(404,'数据提交出错');
        //获取图片后缀名
        $ext = Tools::getBase64ImageExt($form['image']);
        $file_path = 'appletmenu/image/'.date('Ymd').'/'.md5(microtime()).'.'.$ext;
        $file_dir = config('root').'/uploads/appletmenu/image/'.date('Ymd'.'/');
        !file_exists($file_dir) && mkdir($file_dir,0755,true);
        $local_path = Tools::base64decodeImage($form['image'],'/uploads/'.$file_path);
        !$local_path && Tools::returnJson(500,'图片上传失败');
        $reslut = Tools::cosUploadImg($file_path,$local_path);
        $reslut['code'] === 500 && Tools::returnJson(500,'图片上传失败');
        $datas = [
            'applet_menu_title' => $form['title'],
            'applet_menu_image' => urldecode($reslut['url']),
            'applet_menu_url' => $form['url'],
            'is_open' => $form['is_open'],
            'created_at' => time(),
            'updated_at' => time()
        ];
        $applet_menu = Db::table('applet_menu')->insert($datas);
        !$applet_menu && Tools::returnJson(500,'添加失败');
        return $applet_menu;
    }

    /**
     * 修改小程序栏目
     * @param $id 小程序栏目id
     * @param array $form 表单数据
     * @return array|int|null|\PDOStatement|string|\think\Model
     */
    public function update($id,$form=array())
    {
        if($this->request->isPost()){
            !$id && Tools::returnJson(404,'参数错误');
            !$form && Tools::returnJson(404,'数据提交出错');
            $old_applet_menu_image = Db::table('applet_menu')->where('applet_menu_id',$id)
                ->value('applet_menu_image');
            if($old_applet_menu_image != $form['image']){
                //删除酒图片
                Tools::cosDeleteImg($old_applet_menu_image);
                $local_path = Tools::cosUrl2localUrl($old_applet_menu_image);
                @unlink($local_path);

                //获取图片后缀名
                $ext = Tools::getBase64ImageExt($form['image']);
                $file_path = 'appletmenu/image/'.date('Ymd').'/'.md5(microtime()).'.'.$ext;
                $file_dir = config('root').'/uploads/appletmenu/image/'.date('Ymd'.'/');
                !file_exists($file_dir) && mkdir($file_dir,0755,true);
                $local_path = Tools::base64decodeImage($form['image'],'/uploads/'.$file_path);
                !$local_path && Tools::returnJson(500,'图片上传失败');
                $reslut = Tools::cosUploadImg($file_path,$local_path);
                $reslut['code'] === 500 && Tools::returnJson(500,'图片上传失败');
                $form['image'] = urldecode($reslut['url']);
            }
            $datas = [
                'applet_menu_title' => $form['title'],
                'applet_menu_image' => $form['image'],
                'applet_menu_url' => $form['url'],
                'is_open' => $form['is_open'],
                'updated_at' => time()
            ];
            $result = Db::table('applet_menu')->where('applet_menu_id',$id)->update($datas);
            !$result && Tools::returnJson(500,'修改失败');
            return $result;
        }else{
            $applet_menu = Db::table('applet_menu')->where('applet_menu_id',$id)
                ->field('applet_menu_id,applet_menu_title,applet_menu_image,applet_menu_url,is_open')->find();
            return $applet_menu;
        }
    }

    /**
     * 删除小程序栏目
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete($ids = 0)
    {
        !$ids && Tools::returnJson(404,'参数错误');
        $ids = explode(',',$ids);
        $applet_menus = Db::table('applet_menu')->where('applet_menu_id','in',$ids)->select();
        foreach ($applet_menus as $key=>$value){
            Tools::cosDeleteImg($value['applet_menu_image']);
            @unlink(Tools::cosUrl2localUrl($value['applet_menu_image']));
        }
        $count = Db::table('applet_menu')->delete($ids);
        !$count && Tools::returnJson(500,'删除失败');
        return $count;
    }

    /**
     * 排序
     * @param int $id
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function sort($id)
    {
        !$id && Tools::returnJson(404,'参数错误');
        $sort_max = Db::table('applet_menu')->max('applet_menu_sort');
        $result = Db::table('applet_menu')->where('applet_menu_id',$id)
            ->update(['applet_menu_sort'=>++$sort_max,'updated_at'=>time()]);
        !$result && Tools::returnJson(500,'更改排序失败');
        return $result;
    }

    /**
     * 栏目开放
     * @param $id 栏目id
     */
    public function open($id)
    {
        !$id && Tools::returnJson(404,'参数错误');
        $applet_menu = Db::table('applet_menu')->where('applet_menu_id',$id)->find();
        $result = Db::table('applet_menu')->where('applet_menu_id',$id)
            ->update(['is_open'=>!$applet_menu['is_open'],'updated_at'=>time()]);
        !$result && Tools::returnJson(500,'更改开放状态失败');
        return $result;
    }
}