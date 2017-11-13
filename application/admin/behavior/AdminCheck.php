<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 0:18
 */
namespace app\admin\behavior;
use think\controller;
use think\Session;

class AdminCheck{
    use \traits\controller\Jump;
    public function run(&$params){
        $re=Session::has('username');
        if(!$re){
            $this->error('请登录！','admin/login');
        }

    }
}