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
//Route::get('home/nav','');

//Route::rule('/home','/home/index/index');
Route::rule('/admin/product','/admin/product_controller/create','get');
Route::rule('/admin/product/cengji','/admin/product_controller/cengji','get');
//用来做数据的编辑
Route::rule('/admin/product/edit/:id','/admin/product_controller/edit');
//用来做数据的删除
Route::rule('/admin/product/del/:id','/admin/product_controller/delete');
//Route::rule('/admin/product','/admin/product_controller/create','post');





return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
