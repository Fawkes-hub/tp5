<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5
 * Time: 21:36
 */
namespace  app\admin\validate;


use think\Validate;

class Product extends Validate{
    protected $rule =   [
        'catname'  => 'require|max:10',
        'id'  => 'number',
        'pid'  => 'number',
        'status'  => 'require|number|in:0,1',
        'catorder'  => 'number',

    ];

    protected $message  =   [
        'catname.require' => '名称必须填写',
        'catname.max'     => '名称最多不能超过10个字符',
        'status.require' => '状态必须填写',
    ];

    protected $scene = [
        'create' => ['catname','pid','id','status'], //添加
        'edit' => ['catname','id','pid','status'], //编辑
        'catorder' => ['id','catorder'], //排序
    ];



}