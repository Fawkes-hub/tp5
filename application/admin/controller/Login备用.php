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
class Login extends Controller
{
    public function index()
    {

        return $this->fetch();
    }
    public function login(){
        $code=new Code();
        $re_code=$code->get();

        if(strtolower($re_code) == strtolower(input('post.code'))){
            $username=input('post.username');
            $password=md5(input('post.password'));
            $admin=new \app\admin\model\Login();
            $name=$admin->where('username',$username)->find();
            if($name){
                //得到名字正确情况下的密码
                $pass=$admin->where('username',$username)->value('password');
                if($pass == $password){
                    $this->redirect('index/index');
                }else{
                    $this->error('密码错误，请重新输入！');
                }
            }else{
                $this->error('用户不存在！');
            }
        }else{
            $this->error('验证码错误，请重新输入');
        }
    }

    public function code()
    {
        $code = new \code\Code();
        return $code->make();
    }
}