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
    //控制器初始化,判断是否存在session
    public function _initialize()
    {
        $re=Session::get('username');
        if($re==null){
            $this->error('请登录！','/login');
        }
    }



}