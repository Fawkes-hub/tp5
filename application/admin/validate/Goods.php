<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5
 * Time: 21:36
 */
namespace  app\admin\validate;


use think\Validate;

class Goods extends Validate{

    protected $rule =   [
        'goods_name'  => 'require',
        'goods_brand'  => 'require',
        'goods_price'  => 'require|number',
        'goods_color'  => 'require',
        'goods_num'  => 'require|number',
    ];

    protected $scene = [
        'create' => ['goods_name','goods_brand','goods_price','goods_color','goods_num'], //添加
        'edit' => ['catname','id','pid','status'], //编辑
        'catorder' => ['id','catorder'], //排序
    ];



}