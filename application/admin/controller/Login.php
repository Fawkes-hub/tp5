<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 23:13
 */
namespace app\admin\controller;

use code\Code;
use think\Controller;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch('login/index');
    }
    //登录判断
    public function postDologin(){
        if(!captcha_check(input('post.code'))){
            $data=[
                'status'=> 0,
                'msg' => '验证码错误，请重新输入！',
            ];
            return json($data);
        };
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
//                $this->success('登录成功','admin/index/index','','1');
                $data=[
                    'status'=> 1,
                    'msg' => '登录成功',
                ];
                return json($data);
            }else{
                $data=[
                    'status'=> 0,
                    'msg' => '账号或密码错误，请重新输入！',
                ];
                return json($data);
            }
        }else{
            $data=[
                'status'=> 0,
                'msg' => '账号或密码错误，请重新输入！',
            ];
            return json($data);
        }
    }
    //退出
    public function getOutlogin(){
        Session::delete('username');
        $this->redirect('/login');
    }
}