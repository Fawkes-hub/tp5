<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Controller;
use think\Db;
use think\Hook;
use think\Request;
use think\Session;
use think\Validate;
use code\Code;

class User extends AdminCommon
{
    //用户列表页面
    public function getIndex(){
        $user=\app\admin\model\User::all();
        $this->assign('data',$user);
        return $this->fetch('user/index');
    }
    //添加用户页面
    public function getCreate(){
        return $this->fetch('user/create');
    }
    //保存用户
    public function postSave(Request $request){
        $user= $request->except('action,file,__token__,reuser_pass,user_pass');
        //将用户名检测是否有重复的
        $name=$user['user_name'];
        $re=Db::name('user')->where('user_name',$name)->find();
        if($re){
            $data = [
                'status' => 1,
                'msg' => '用户名已存在，请重新输入',
            ];
            return json($data);
        }
        $password=md5(input('post.user_pass'));
        $user['user_pass']=$password;
        $users=new \app\admin\model\User();
        $re=$users->save($user);
        if($re){
            $data = [
                'status' => 0,
                'msg' => '用户添加成功',
            ];
            return json($data);
        }else{
            $data = [
                'status' => 2,
                'msg' => '出现未知错误，请稍后重试',
            ];
            return json($data);
        }
    }
    //用户头像上传
    public function postUser_pic(){
        $file=\request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/user_pic');
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
    //用户状态的停用
    public function postUser_stop($id)
    {
        //
        $admin=new \app\admin\model\User();
        $admin->save([
            'status'  => '1',
        ],['id' => $id]);
        return ;
    }
    //用户状态的启用
    public function postUser_start($id)
    {
        //
        $admin=new \app\admin\model\User();
        $admin->save([
            'status'  => '0',
        ],['id' => $id]);
        return ;
    }


}