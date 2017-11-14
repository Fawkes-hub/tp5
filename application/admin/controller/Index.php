<?php
namespace app\admin\controller;

use think\Controller;
use think\Hook;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        $username = Session::get('username');
        $this->assign('username',$username);
        return $this->fetch();
    }




}
