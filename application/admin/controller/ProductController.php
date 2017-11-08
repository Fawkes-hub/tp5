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
     * 创建分类
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
     * 显示分类详情
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //用来顶级列表的遍历
        $product=new Product();
        $data=$product->tree();
        $data=$product->where('id',$id)->select();
        return $this->fetch('product/edit',['data'=>$data]);
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

        $data1=Request::instance()->except(['__token__','id']);
        $product=new Product();
        $re=$product->where('id',$id)->update($data1);
        if($re){
//            $this->redirect('product/index');
            $this->success('数据修改成功！！','product/index','','1');
        }else{
            $this->error('数据修改失败！！');
        }

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $request = Request::instance()->get('id');

        dump($request);
    }
}
