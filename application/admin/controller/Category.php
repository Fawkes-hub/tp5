<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\console\Input;
use app\admin\model\Product;
class Category extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $product=new Product();
        $data=$product->tree();
        return $this->fetch('category/index',['data'=>$data]);

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $product=new Product();
        $data=$product->where('pid',0)->select();
        return $this->fetch('category/create',['data'=>$data]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data=Request::instance()->except(['__token__']);
        $validate = Validate('Category');
        $result = $validate->scene('create')->check($data);
        if (!$result) {
            $this->error($validate->getError());
            exit;
        }
        //静态方法调用数据库，添加数据
        $product= \app\admin\model\Product::create($data);
        if($product){
            $this->success('添加成功','category');
        }else{
            $this->error('添加失败');
        }
        return $data;
    }

    /*进行排序的方法*/
    public function update_order(Request $request, $id)
    {
        $data=Request::instance()->except(['__token__']);
        $id=($data['id']);
        $product=Product::get($id);
        $product->catorder=$data['catorder'];
        $re=$product->save();
        if($re){

            $data = [
                'status' => 0,
                'msg' => '分类列表操作成功',
            ];
        }else{

            $data = [
                'status' => 1,
                'msg' => '分类列表操作失败，请稍候重试',
            ];
        }
        return $data;
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

        $data=Request::instance()->except(['__token__']);
        $id=$data['id'];

        $product=new Product();
        $data=$product->tree();
        $data=$product->where('id',$id)->select();
        //采用完整路径去调用模板
        return $this->fetch(APP_PATH.request()->module().'/admin/view/category/edit.html',['data'=>$data]);
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
