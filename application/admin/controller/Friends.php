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
    //加载友情链接
             public function getindex(){
     //创建请求
                 $request=request();
     //查询出数据
                $data=Db::table('tp_firends')->select();
                return $this->fetch("friends/index",["data"=>$data]);
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
                    $data=$request->only(['firends_name','firends_path','firends_status','firends_sta']);
         //把路径赋值给$path
                      $path=$data['firends_path'];
         //判断前缀是否是http://和https://
                     if ((substr($path, 0, 7) != 'http://') && (substr($path, 0, 8) != "https://")) {
         //如不是就拼接
                     $path = "http://" .$path;
                         }
          //把赋值的结果承储起来
                      $data["firends_path"] = $path;
          //调用validate
                    $result=$this->validate($request->param(),'Friends');
                    if(true!==$result){
                       $this->error($result,'friends/add');
                   }
           //把数据承储到数据库里
                   $info=Db::table("tp_firends")->insert($data);
                   //var_dump($s) ;exit;
                    if($info){
                       $this->success("添加成功",'friends/index');
                    }else{
                        $this->error("添加失败",'friends/add');
                    }

                   }
            //执行删除方法
                    public function getDelete(){
             //获取请求方式
                        $request=request();
                        $id=$request->param("id");
                        var_dump($id);
                    }
        }

