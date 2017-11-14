<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 23:13
 */
namespace app\admin\controller;

use app\admin\AdminCommon;
use code\Code;
use think\Controller;
use think\Session;

class Login extends AdminCommon
{
    public function index()
    {
        return $this->fetch();
    }
    public function dologin(){
        $code=new Code();
        $re_code=$code->get();
        if(strtolower($re_code) == strtolower(input('post.code'))){
            $username=input('post.username');
            $password=md5(input('post.password'));
            $admin=new \app\admin\model\Login();
            $name=$admin->where(['username'=>$username,'status'=>0])->find();
//            $adminrole=$admin->where('username',$username)->value('adminrole');
            $role= \app\admin\model\Admin::get(['username' => $username]);
            $adminrole=$role->adminrole;
            if($name){
                //得到名字正确情况下的密码
                $pass=$admin->where('username',$username)->value('password');
                if($pass == $password){
                    Session::set('username',$username);
                    Session::set('adminrole',$adminrole);
                    $this->redirect('index/index');
                }else{
                    $this->error('账号或密码错误，请重新输入！');
                }
            }else{
                $this->error('账号或密码错误，请重新输入！');
            }
        }else{
            $this->error('验证码错误，请重新输入');
        }
    }

    public function code()
    {
        $code = new \code\Code();
        $code->make();
    }

    public function outlogin(){
        Session::delete('username');
        $this->redirect('login/index');
    }
}