<?php
/**
 * Created by PhpStorm.
 * User: 冯轲
 * Date: 2017-11-3
 * Time: 10:16
 */

namespace app\home\controller;

use think\Controller;

class Product extends Controller
{
    public function index()
    {
        return $this->fetch();
    }


}
