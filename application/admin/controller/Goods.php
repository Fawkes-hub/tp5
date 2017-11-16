<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Db;
use think\Request;

class Goods extends AdminCommon
{
    public function index()
    {
        return $this -> fetch();
    }

    public function create()
    {
        $Category=new \app\admin\model\Category();
        $data=$Category->tree();
        $this->assign('data',$data);
        return $this -> fetch();
    }
    public function save(Request $request){
        $input= $request->except('goods_color');
        $re=$request->param('goods_color/a');
        $input['goods_color']=json_encode($re);
        $re=\app\admin\model\Goods::create($input);
        if($re){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }





    }
    //图片上传的处理
    public function goods_pic(){
        $file=\request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $path =  $info->getSaveName();
                return json(array('status'=>1,'path'=>$path,'msg'=>'图片上传成功'));
            }else{
                // 上传失败获取错误信息
//                echo $file->getError();
                return json(array('status'=>0,'msg'=>'上传失败'));
            }
        }
    }


}




?>