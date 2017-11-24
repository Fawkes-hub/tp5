<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Db;
use think\Request;

class Goods extends AdminCommon
{
    public function getIndex()
    {
        //商品的展现
        $goods=\app\admin\model\Goods::all();
        $this->assign('data',$goods);
        return $this -> fetch('goods/index');
    }
    //商品的创建页面
    public function getCreate()
    {
        $Category=new \app\admin\model\Category();
        $data=$Category->tree();
        $this->assign('data',$data);
        return $this -> fetch('goods/create');
    }
    //商品的保存
    public function postSave(Request $request){

        $input= $request->except('goods_color,file,action');


        $re=\app\admin\model\Goods::create($input);
        //存入颜色到颜色表
        $id=$re->getLastInsID();
        $color=$request->param('goods_color/a');
        foreach ($color as $key=>$value){
            $colors[$value]=1;
        }
        $colors['goods_id']=$id;
        Db::name('goods_color')->insert($colors);
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
    public function postGoods_pic(){
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
    public function getEdit($id){
        //目录的得到
        $Category=new \app\admin\model\Category();
        $data=$Category->tree();
        $this->assign('data',$data);
        //商品的得到
        $goodsArr=new  \app\admin\model\Goods;
        $goods=$goodsArr->where('id',$id)->select();
        $this->assign('goods',$goods);
        //颜色的传值
        $color=Db::name('goods_color')->where('goods_id',$id)->find();
        $this->assign('color',$color);
        return $this->fetch('goods/edit');
    }
    //编辑写入
    public function postUpdate(Request $request){
        $input=($request->except('goods_color,file,action'));
        $id=$input['id'];
        //存入颜色到颜色表
        $color=$request->param('goods_color/a');
        $colors=[
            'black'=>null,
            'white'=>null,
            'red'  =>null,
            'yellow'=>null,
            'green'=>null,
            'other'=>null,
        ];
        $color_id=Db::name('goods_color')->where('goods_id',$id)->value('id');
        foreach ($color as $key=>$value){
            $colors[$value]=1;
        }
        $colors['goods_id']=$id;
        Db::name('goods_color')->where('id',$color_id)->update($colors);

        //通过id更新商品信息

        //需要判断是否有新的图片上传，如果有就必须要删除已经存储的图片
        $old_pic=$input['old_goods_pic'];
        $pic=$input['goods_pic'];
        if($old_pic != $pic) {
            //不等于就说明上传了新的图片，就删除原有的图片
            $path = './uploads/' . $old_pic;
            if (file_exists($path) && is_file($path)) {

                //存在就删除图片
                if (unlink($path)) {
                    //图片删除成功才进行数据库更新
                    unset($input['old_goods_pic']);
                    $re = Db::name('goods')->update($input);
                    if($re>0){
                        $data = [
                            'status' => 0,
                            'msg' => '修改成功'
                        ];
                    }
                }else {
                    $data = [
                        'status' => 1,
                        'msg' => '图片修改失败'
                    ];
                }
                return json($data);
            } else {
                //就算是图片不存在，也要进行修改
                unset($input['old_goods_pic']);
                $re = Db::name('goods')->update($input);
                if($re>0){
                    $data = [
                        'status' => 0,
                        'msg' => '已重新上传图片并更新'
                    ];
                }
                return json($data);
            }

        }else{
            //不上传新图片的更新
            unset($input['old_goods_pic']);
            $re = Db::name('goods')->update($input);
            if($re>0){
                $data = [
                    'status' => 0,
                    'msg' => '商品更新成功'
                ];
            }
            return json($data);
        }

    }


    public function postDelete($id){
        //商品的删除
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
    public function postGoods_stop($id)
    {
        //
        $admin=new \app\admin\model\Goods();
        $admin->save([
            'goods_status'  => '1',
        ],['id' => $id]);
        return ;
    }
    //商品的启用
    public function postGoods_start($id)
    {
        //
        $admin=new \app\admin\model\Goods();
        $admin->save([
            'goods_status'  => '0',
        ],['id' => $id]);
        return ;
    }
    //商品详情
    public function getShow($id){
        echo 'aa';

        $re=input();
        dump($id);
    }


}




?>