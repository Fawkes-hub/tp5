<?php
namespace app\admin\controller;
use think\Db;
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2017/12/1
 * Time: 13:34
 */
use \app\admin\AdminCommon;
class Friends extends AdminCommon{
    public function getindex(){
        //加载友情链接
        return $this->fetch("friends/index");
    }
    //添加友情链接
    public function getadd(){
        return $this->fetch("friends/add");
    }
    //执行添加
        public function postDoadd(){
            //创建请求
                $request=request();
             //获取参数
                 $friend=$request->except("action");
            //判断传过去的值是否是空值
                if(empty($name=$friend['firends_name'])){
                    $this->error("请填写完整","friends/add");
                 }else{
                     if(empty($path=$friend['firends_path'])){
                     $this->error("请填写完整","friends/add");
                     }else {
                         $a=substr($path, 0, 7);
                         var_dump($path);
                         var_dump($a);
                         //判断是否以http://开头
                         if ((substr($path, 0, 7) != 'http://') || (substr($path, 0, 8) != "https://")) {
                             $paths = "http://" .$path;
                         }
                         $friend["firends_path"] = $paths;
                     }
                         //$friend["firends_path"] = $path;
                         // $result=Db::table("tp_firends")->insert($friend);
                         // $this->success("填写成功","friends/index");
                         var_dump($friend);
                     }
                }



        }

