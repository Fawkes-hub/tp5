<?php

namespace app\admin\model;

use think\Model;

class Category extends Model
{
    //
    protected $table = 'tp_category';

    public function tree()
    {
        //这个表示那拿到的数据库里面的内容
        $product=$this->order('catorder','asc')->select();
        return  $this->getTree($product);

    }
    //无限极递归
    public function getTree($data,$pid=0,$level=0){
        static $arr=array();
        foreach($data as $key=>$value){
            if($value['pid'] == $pid){
                $value['level']=$level;     //用来作为在模版进行层级的区分
                $arr[] = $value;            //把内容存进去
                $this->getTree($data,$value['id'],$level+1);    //回调进行无线递归
            }
        }
        return $arr;

    }

    //获取器修改状态的显示值
    public function getStatusAttr($value)
    {
        $status = [1=>'隐藏',0=>'显示'];

        return $status[$value];
    }

}
