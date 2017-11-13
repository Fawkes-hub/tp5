<?php
namespace app\admin\controller;

use think\Controller;
use think\Hook;

class Index extends Controller
{
    public function index()
    {
        Hook::exec('app\\admin\\behavior\\AdminCheck','run',$params);
        return $this->fetch();
    }




}
