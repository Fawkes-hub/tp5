<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5
 * Time: 21:36
 */
namespace  app\admin\validate;


use think\Validate;

class Admin extends Validate{
    protected $rule = [
        'username'  =>  'require|token',
    ];

    protected $message  =   [
        'username.token' => '验证错误，请重新提交！',
    ];

}