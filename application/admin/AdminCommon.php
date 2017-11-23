<?php
/**
 * Created by PhpStorm.
 * User: 冯轲
 * Date: 2017-11-14
 * Time: 10:07
 */
namespace app\admin;
use think\Controller;
use think\Hook;
use think\Session;

class AdminCommon extends Controller{
    //控制器初始化
    public function _initialize()
    {
        $re=Session::has('username');
        if(!$re){
            $this->error('请登录！','login/index');
        }
    }



}