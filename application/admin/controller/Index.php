<?php
namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Controller;
use think\Hook;
use think\Session;

class Index extends AdminCommon
{

    public function index()
    {
        return $this->fetch();
    }




}
