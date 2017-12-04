<?php
/**
 * Created by PhpStorm.
 * User: 冯轲
 * Date: 2017-12-4
 * Time: 8:54
 */
namespace app\home\controller;

use app\admin\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use ucpaas\Ucpaas;

class Cart extends Controller
{
    public function getIndex(){
        return $this->fetch('cart/index');
    }
}