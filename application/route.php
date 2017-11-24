<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use \think\Route;
//进入后台
Route::get('/','admin/index/index');
//后台用户的路由
Route::controller('admin/admin','admin/Admin');
//后台列表的路由
Route::controller('admin/category','admin/category');
//后台商品的路由
Route::controller('admin/goods','admin/goods');

