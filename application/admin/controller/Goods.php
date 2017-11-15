<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Request;

class Goods extends AdminCommon
{
    public function index(){
        return $this -> fetch();
    }

    public function create(){

        return $this -> fetch();
    }
    //图片上传的处理
    public function goods_pic(){
        $aa=\request()->file('image');
        dump($aa);
        if($aa){
            $info = $aa->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $aa->getError();
            }
        }
    }


}




?>