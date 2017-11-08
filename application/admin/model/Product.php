<?php

namespace app\admin\model;

use think\Model;

class Product extends Model
{
    //
    protected $table = 'tp_category';

    public function tree()
    {
        //这个表示那拿到的数据库里面的内容
        $product=$this->order('catorder','asc')->select();
        //return返回调用的地方，把内容赋值
       return  $this->getTree($product,'catname','id','pid',0);

    }

    public function getTree($data,$field_name,$field_id='id',$field_pid='pid',$pid=0){
        $arr=array();
        foreach ($data as $key=>$val) {
            //如果pic=0，进入其中
            if($val->$field_pid==$pid){
                $data[$key]["_".$field_name]=$data[$key]["$field_name"];
                $arr[] = $data[$key];
            }
            //如果不等于0,
            foreach ($data as $k=>$v) {
                if($v->$field_pid==$val->$field_id){
                    $data[$k]["_".$field_name]='┣━━━'.$data[$k]["$field_name"];
                    $arr[] = $data[$k];
                }
            }
        }
        //dd($arr);
        return $arr;
    }
    //获取器修改状态的显示值
    public function getStatusAttr($value)
    {
        $status = [1=>'隐藏',0=>'显示'];

        return $status[$value];
    }

}
