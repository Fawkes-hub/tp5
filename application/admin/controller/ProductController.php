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
        $product=new Product();
        $data=$product->tree();
        return $this->fetch('product/index',['data'=>$data]);

    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $product=new Product();
        $data=$product->where('pid',0)->select();
//        $data=$product->tree();
//        echo '<pre>';
//        print_r($data);
        return $this->fetch('product/create',['data'=>$data]);
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
            $this->success('数据添加成功！！','product/index','','1');
        }else{
            $this->error('数据添加失败！！');
        }


    }
    /*进行排序的方法*/
    public function changeOrder(Request $request){
//            $input= I('id');
//        $input= input('post.');
        $aa=\input('post.');
        dump($aa);
//        return json_encode($input);
        /*$id=$this->index('get.id');
        $product=Product::get($id);
        $product->catorder=input('get.catorder');
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
        return $input;*/
        // echo $input['cate_order'];
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
