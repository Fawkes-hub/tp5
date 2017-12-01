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
Route::get('/','home/index/index');
//用户检测登录的路由
Route::get('/login','admin/login/index');
//用来登录的对应操作
Route::controller('admin/login','admin/login');
//后台用户的路由
Route::controller('admin/admin','admin/Admin');
//后台列表的路由
Route::controller('admin/category','admin/category');
//后台商品的路由
Route::controller('admin/goods','admin/goods');
//后台的前台用户管理
Route::controller('admin/user','admin/User');
//后台的友情链接路由
Route::controller('admin/friends','admin/Friends');
//后台公告路由
Route::controller('admin/notices','admin/Notices');


//前台
Route::controller('/home/login','home/login');

