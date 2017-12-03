<?php
namespace app\home\controller;

use app\admin\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use ucpaas\Ucpaas;

class Login extends Controller
{
    //登录页
    public function getIndex()
    {
        return $this->fetch('login/index');
    }
    //登录方法
    public function postLogin(){
        $user_name=input('post.user_name');
        $user_pass=md5(input('post.user_pass'));
        //首先进行用户名判断
        $user=new User();
        $pass=$user->where('user_name',$user_name)->value('user_pass');
        $id=$user->where('user_name',$user_name)->value('id');
        if($pass==$user_pass){
            //登录成功来就存入Session;
            Session::set('user_name',$user_name);
            Session::set('user_id',$id);
            $data=[
                'status' =>0,
                'msg'   =>'登录成功',
            ];
        }else{
            $data=[
                'status' =>1,
                'msg'   =>'用户名或密码错误',
            ];
        }
        return json($data);
    }
    //退出
    public function getLogout(){
        Session::delete('user_name');
        $this->redirect('/');
    }
    //注册
    public function getCreate(){
        return $this->fetch('login/create');
    }
    //保存用户
    public function postSave(Request $request){
        //先获取手机验证码，进行调用方法的验证
//        $phone=$request->only('user_phone');
        $req=$request->except(['action','reuser_pass','phone_code','user_pass']);
        $req['user_pass']=md5(input('post.user_pass'));
        /*//存入到属性中，进行调用
//        $this->phone=$phone['user_phone'];
        //实例化调用这个参数
//        $ucpass=$this->postPhoneCode();
        //这个是存入属性的验证码内容
//        print_r($this->param);
        //最终获取得到的反馈数据，然后返回去进行页面的按钮变化
//        $ucpass=json_decode($ucpass);
        //上面是欧佳俊接下来做的，我不做了*/
        //如果验证码验证成功，进行下一步
        $code=1;
        if($code != input('post.phone_code')){
            //如果传过来的验证码不正确，就返回数据
            $data=[
                'status'=>1,
                'msg'   =>'验证码不正确，请重新输入',
            ];
            return json($data);
        }
        //验证码正确的再进行下一步输出
        $user=new \app\home\model\Login();
        $save=$user->save($req);
        if($save){
            $data=[
                'status' => 0,
                'msg'    => '用户创建成功',
            ];
        }else{
            $data=[
                'status' => 2,
                'msg'    => '用户创建失败，请稍后重试',
            ];
        }
        return json($data);
    }
    //用户名是否重复的检测
    public function getUser(Request $request){
        $user=$request->except('action');
        $res=Db::name('user')->where($user)->find();
        if($res){
            $data=[
                'status' => 1,
                'msg'   => '用户名已存在，请重新输入',
            ];
        }else{
            $data=[
                'status' => 0,
                'msg'   => '用户名可用',
            ];
        }
        return json($data);
    }
    //验证码的结果调用
    public function postPhoneCode(){
        //获取传送过来的手机号
        $phone='+86'.$this->phone;
        //初始化必填
        $options['accountsid']='5fdc548f5f3324242e9926cb422104b9';
        $options['token']='341cbb394710e905b982fb2835cd4f0a';
        //初始化 $options必填
        $ucpass = new Ucpaas($options);
        //开发者账号信息查询默认为json或xml
        header("Content-Type:text/html;charset=utf-8");

        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $appId = "9bd0217ba6ac412dbf87868ba3ab048f";
        $to = "{$phone}";
        $templateId = "227656";
        $param=rand(100000,999999);
        //将验证码存入到属性，用来验证
        $this->param=$param;
        //返回验证结果
        return $ucpass->templateSMS($appId,$to,$templateId,$param);


    }

}
