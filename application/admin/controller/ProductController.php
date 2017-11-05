<?php

namespace app\admin\controller;

use think\console\Input;
use think\Controller;
use think\Request;
use app\admin\model\Product;
class ProductController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {


        return $this->fetch('product/index');

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $product=new Product();
        $data=$product->tree();
        return $this->fetch('product/create',compact('data',$data));
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
        $validate = Validate('Product');
        $result = $validate->scene('create')->check($data);
        if (!$result) {
            $this->error($validate->getError());
            exit;
        }
        //静态方法调用数据库，添加数据
        $product= \app\admin\model\Product::create($data);
        if($product){
            $this->success('数据添加成功！！');
        }else{
            $this->error('数据添加失败！！');
        }


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
