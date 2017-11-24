<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Controller;
use think\Hook;
use think\Request;
use think\Session;
use think\Validate;
use code\Code;

class Admin extends AdminCommon
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function getIndex()
    {

        //查询得到数据库所有的数据
        $admin=\app\admin\model\Admin::all();
        $this->assign('data',$admin);
        return view('admin/index');
    }
    public function getLogin()
    {
        return $this->fetch('admin/login');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function getCreate()
    {
        //
        return view('admin/create');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function postSave(Request $request)
    {
        //先进行传输的数据的验证
        $validate=\validate('Admin');
        $vali = $validate->check(input('post.'));
        if (!$vali) {
            $this->error($validate->getError());
            exit;
        };
        $result=$request->except(['repassword','password','__token__','action']);
        //需要得到传递过来数据的验证
        $password=md5(input('post.password'));
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
                'status' => 0,
                'msg' => '添加',
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
    public function getEdit($id)
    {
        //
        $admin=new \app\admin\model\Admin();
        $data=$admin->where('id',$id)->select();
        $this->assign('data',$data);
        return view('admin/edit');
    }
    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function postUpdate(Request  $request,$id)
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
            $result=$request->except(['repassword','password','__token__','oldpassword','action']);
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
    public function postAdmin_stop($id)
    {
        //
        $admin=new \app\admin\model\Admin();
        $admin->save([
            'status'  => '1',
        ],['id' => $id]);
        return ;
    }
    //状态的启用
    public function postAdmin_start($id)
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
    public function postDelete($id)
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
