<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use Prophecy\Doubler\ClassPatch\SplFileInfoPatch;
use think\Db;
use think\File;
use think\Request;

class Goods extends AdminCommon
{
    public function index()
    {
        //商品的展现
        $goods=\app\admin\model\Goods::all();
        $this->assign('data',$goods);
        return $this -> fetch();
    }

    public function create()
    {
        $Category=new \app\admin\model\Category();
        $data=$Category->tree();
        $this->assign('data',$data);
        return $this -> fetch();
    }
    public function save(Request $request){

        $input= $request->except('goods_color,file');
        $re=$request->param('goods_color/a');
        $input['goods_color']=json_encode($re);
        $re=\app\admin\model\Goods::create($input);
        if($re){
            //返回数据，进行判断是否添加成功
            $data=[
                'status' => 1,
                'msg' => '添加成功',
            ];

        }else{
            $data=[
                'status' => 0,
                'msg' => '出现未知错误，添加失败',
            ];
        };
        //必须是json数据的返回
        return json($data);
    }
    //图片上传的处理
    public function goods_pic(){
        $file=\request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $path =  $info->getSaveName();
                return json(array('status'=>1,'path'=>$path,'msg'=>'图片上传成功'));
            }else{
                // 上传失败获取错误信息
//                echo $file->getError();
                return json(array('status'=>0,'msg'=>'上传失败'));
            }
        }
    }

    //商品的编辑
    public function edit($id){
        //目录的得到
        $Category=new \app\admin\model\Category();
        $data=$Category->tree();
        $this->assign('data',$data);
        //商品的得到
        $goodsArr=new  \app\admin\model\Goods;
        $goods=$goodsArr->where('id',$id)->select();
        $this->assign('goods',$goods);
        //颜色的传值
        foreach ($goods as $val){
            $color=$val['goods_color'];
        }
        dump($color);
        $color=json_decode($color);
        $this->assign('color',$color);

        exit;
        return $this->fetch();
    }

    public function delete($id){
        //商品的删除
//        $re=\app\admin\model\Admin::destroy($id);
        $goods=new \app\admin\model\Goods;
        $path=$goods->where('id',$id)->value('goods_pic');
        $path='./uploads/'.$path;
        $re=\app\admin\model\Goods::destroy($id);
        if($re){
            //如果数据库商品删除成功就删除图片目录
            //$path存在，并且必须是文件不是目录
            if(file_exists($path) && is_file($path)){
                //存在就删除图片
                if(unlink($path)){
                    $data=[
                        'status'=>0,
                        'msg' => '删除成功'
                    ];
                }else{
                    $data = [
                        'status'=>1,
                        'msg' => '文件删除失败'
                    ];
                }
            }else{
                $data = [
                    'status'=>2,
                    'msg' => '文件不存在'
                ];
            }
        }else{
            $data = [
                'status'=>3,
                'msg' => '出现未知错误'
            ];
        }
        return json($data);
    }

    //商品的停用
    public function goods_stop($id)
    {
        //
        $admin=new \app\admin\model\Goods();
        $admin->save([
            'goods_status'  => '1',
        ],['id' => $id]);
        return ;
    }
    //商品的启用
    public function goods_start($id)
    {
        //
        $admin=new \app\admin\model\Goods();
        $admin->save([
            'goods_status'  => '0',
        ],['id' => $id]);
        return ;
    }


}




?>