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

class AdminCommon extends Controller{
    public function index(){
        Hook::exec('app\\admin\\behavior\\AdminCheck','run',$params);
    }

}