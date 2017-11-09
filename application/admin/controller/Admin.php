<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Admin extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询得到数据库所有的数据
        $admin=\app\admin\model\Admin::all();
        $this->assign('data',$admin);
        return view();
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
        //
        $data=$request->except('repassword');
        dump($data);
        exit;
        $re=\app\admin\model\Admin::create($data);
        if($re){
            $data =
                [
                    'status'=> 1,
                    'msg' => '添加成功',
                ];
        }else{
            $data =
                [
                    'status'=> 0,
                    'msg' => '添加失败',
            ];
        }
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
    public function edit()
    {
        //
        return view();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
