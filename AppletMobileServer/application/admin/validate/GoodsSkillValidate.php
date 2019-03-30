<?php
/**
 * Created by PhpStorm.
 * User=> JS_chen
 * Date=> 2019/1/3
 * Time=> 16=>36
 */

namespace app\admin\validate;


use think\Validate;

class GoodsSkillValidate extends Validate
{
    /**
     * 商品验证器
     */
    protected $rule = [
        'goods_cate_id'  =>  'require',
        'goods_skill_name' => 'require',
        'goods_skill_desc' => 'require',
        'goods_skill_price' => 'require|min:0',
        'goods_skill_sprice' => 'require',
        'goods_skill_images' => 'require',
        'goods_skill_stock' => 'require|min:0',
        'goods_skill_express' => 'require|min:0',
        'goods_skill_detail' => 'require',
        'goods_skill_param' => 'require',
        'goods_skill_time' => 'require',
    ];

    protected $message  =   [
        'goods_cate_id.require'  =>  '未选择商品分类',
        'goods_skill_name.require' => '商品名称不能为空',
        'goods_skill_desc.require' => '商品详情不能为空',
        'goods_skill_price.require' => '商品定价不能为空',
        'goods_skill_price.min' => '商品定价不能小于0',
        'goods_skill_sprice.require' => '商品售价不能为空',
        'goods_skill_sprice.min' => '商品售价不能小于0',
        'goods_skill_images.require' => '商品轮播图不能为空',
        'goods_skill_stock.require' => '商品库存不能为空',
        'goods_skill_stock.min' => '商品库存不能小于0',
        'goods_skill_express.require' => '快递费不能为空',
        'goods_skill_express.min' => '快递费不能小于0',
        'goods_skill_detail.require' => '商品详情不能为空',
        'goods_skill_param.require' => '商品参数不能为空',
        'goods_skill_time.require' => '秒杀时间不能为空'
    ];
}