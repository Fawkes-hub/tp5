<?php

namespace app\admin\controller;

use think\Controller;
use think\Hook;
use think\Request;
use think\Session;
use think\Validate;
use code\Code;

class Admin extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        Hook::exec('app\\admin\\behavior\\AdminCheck','run',$params);
        //查询得到数据库所有的数据
        $admin=\app\admin\model\Admin::all();
        $this->assign('data',$admin);
        return view();
    }
    public function login()
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
            $name=$admin->where('username',$username)->find();
            if($name){
                //得到名字正确情况下的密码
                $pass=$admin->where('username',$username)->value('password');
                if($pass == $password){
                    Session::set('username',$username);
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
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //先进行传输的数据的验证
        $validate=\validate('Admin');
        $vali = $validate->check(input('post.'));
        if (!$vali) {
            $this->error($validate->getError());
            exit;
        };
        $result=$request->except(['repassword','password','__token__']);
        //需要得到传递过来数据的验证
        $password=md5($request->param('post.password'));
        $result['password']=$password;
        $re=\app\admin\model\Admin::create($result);
        if($re){
            //返回数据，进行判断是否添加成功
            $data=[
                'status' => 1,
                'msg' => '添加成功',
            ];

        }else{
            $data=[
                'status' => 1,
                'msg' => '添加成功',
            ];
        };
        //必须是json数据的返回
        return json($data);
    }


    /**
     * 显示管理员的详情
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function show()
    {
        return view();
    }


    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $admin=new \app\admin\model\Admin();
        $data=$admin->where('id',$id)->select();
        $this->assign('data',$data);
        return view();
    }
    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request  $request,$id)
    {
        //进行传值的验证
        $validate=\validate('Admin');
        $va = $validate->check(input('post.'));
        if (!$va) {
            $this->error($validate->getError());
            exit;
        };
        //当password为空时，表示未修改密码
        if(empty(input('post.password'))){
            $result=$request->except(['repassword','password','__token__','oldpassword']);
            $result['password']=input('post.oldpassword');
            $admin=new \app\admin\model\Admin();
            $re=$admin->save($result,['id' => $id]);
            if($re){
                //返回数据，进行判断是否添加成功
                $data=[
                    'status' => 1,
                    'msg' => '更新成功',
                ];
            }else{
                $data=[
                    'status' => 0,
                    'msg' => '更新失败',
                ];
            };
            return json($data);
        }else{
            //如果输入了新的密码，就表示更新密码
            $result=$request->except(['repassword','password','__token__','oldpassword']);
            //需要得到传递过来数据的验证
            $password=md5(input('post.password'));
            $result['password']=$password;
            $admin=new \app\admin\model\Admin();
            $re=$admin->save($result,['id' => $id]);
            if($re){
                //返回数据，进行判断是否添加成功
                $data=[
                    'status' => 1,
                    'msg' => '更新成功',
                ];
            }else{
                $data=[
                    'status' => 0,
                    'msg' => '更新失败',
                ];
            };
            return json($data);
        }
    }
    //状态的停用
    public function admin_stop($id)
    {
        //
        $admin=new \app\admin\model\Admin();
        $admin->save([
            'status'  => '1',
        ],['id' => $id]);
        return ;
    }
    //状态的启用
    public function admin_start($id)
    {
        //
        $admin=new \app\admin\model\Admin();
        $admin->save([
            'status'  => '0',
        ],['id' => $id]);
        return ;
    }



    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {

        $re=\app\admin\model\Admin::destroy($id);
        if($re){
            //返回数据，进行判断是否添加成功
            $data=[
                'status' => 1,
                'msg' => '删除成功',
            ];
        }else{
            $data=[
                'status' => 0,
                'msg' => '删除失败',
            ];
        };
        return json($data);

    }
}
