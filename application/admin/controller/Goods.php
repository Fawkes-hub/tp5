<?php

namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Db;
use think\Exception;
use think\Request;
use think\Validate;

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
        //用表关联的方法进行数据存储
        $goods           = new \app\admin\model\Goods();
        $goods->data($input);
        if ($goods->save()) {
            // 写入关联数据
            $color=($request->param('goods_color/a') !== null)?$request->param('goods_color/a'):array();
            foreach ($color as $key=>$value){
                $colors[$value]=1;
            }
            $goods->profile()->save($colors);
            //返回数据，进行判断是否添加成功
            $data=[
                'status' => 1,
                'msg' => '添加成功',
            ];
        } else {
            $data=[
                'status' => 0,
                'msg' => '出现未知错误，添加失败',
            ];
        }
        return json($data);
    }

        /*
        $input= $request->except('goods_color,file,action');
        $re=\app\admin\model\Goods::create($input);
        //存入颜色到颜色表
        $id=$re->getLastInsID();
        $color=($request->param('goods_color/a') !== null)?$request->param('goods_color/a'):array();
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
        */

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
    public function postUpdate(Request $request)
    {
        $input = ($request->except('goods_color,file,action'));
        $id = $input['id'];
        //需要判断是否有新的图片上传，如果有就必须要删除已经存储的图片
        $old_pic = $input['old_goods_pic'];
        $pic = $input['goods_pic'];
        if ($old_pic != $pic) {
            //不等于就说明上传了新的图片，就删除原有的图片
            $path = './uploads/' . $old_pic;
            if (file_exists($path) && is_file($path)) {
                //存在就删除图片
                if (unlink($path)) {
                    //图片删除成功才进行数据库更新
                    unset($input['old_goods_pic']);
                    $goods = \app\admin\model\Goods::get($id);
                    $goods->data($input);
                    if ($goods->save()) {
                        //存入颜色到颜色表
                        $color = ($request->param('goods_color/a') !== null) ? $request->param('goods_color/a') : array();
                        $colors = [
                            'black' => null,
                            'white' => null,
                            'red' => null,
                            'yellow' => null,
                            'green' => null,
                            'other' => null,
                        ];
                        foreach ($color as $key => $value) {
                            $colors[$value] = 1;
                        }
                        // 更新颜色表的数据
                        $goods->profile->save($colors);
                        $data = [
                            'status' => 0,
                            'msg' => '修改成功'
                        ];
                    } else {
                        $data = [
                            'status' => 1,
                            'msg' => '商品修改失败'
                        ];
                    }
                    return json($data);
                }
                } else {
                //就算是老图片不存在，也要进行修改
                unset($input['old_goods_pic']);
                $goods = \app\admin\model\Goods::get($id);
                $goods->data($input);
                if ($goods->save()) {
                    //存入颜色到颜色表
                    $color = ($request->param('goods_color/a') !== null) ? $request->param('goods_color/a') : array();
                    $colors = [
                        'black' => null,
                        'white' => null,
                        'red' => null,
                        'yellow' => null,
                        'green' => null,
                        'other' => null,
                    ];
                    foreach ($color as $key => $value) {
                        $colors[$value] = 1;
                    }
                    // 更新颜色表的数据
                    $goods->profile->save($colors);
                    $data = [
                        'status' => 0,
                        'msg' => '已重新上传图片并更新'
                    ];

                } else {
                    $data = [
                        'status' => 0,
                        'msg' => '商品修改失败',
                    ];
                }
                return json($data);
            }
        }else{
                //不上传新图片的更新
                unset($input['old_goods_pic']);
                $goods = \app\admin\model\Goods::get($id);
                $goods->data($input);
                if ($goods->save()) {
                    //存入颜色到颜色表
                    $color = ($request->param('goods_color/a') !== null) ? $request->param('goods_color/a') : array();
                    $colors = [
                        'black' => null,
                        'white' => null,
                        'red' => null,
                        'yellow' => null,
                        'green' => null,
                        'other' => null,
                    ];
                    foreach ($color as $key => $value) {
                        $colors[$value] = 1;
                    }
                    // 更新颜色表的数据
                    $goods->profile->save($colors);
                    $data = [
                        'status' => 0,
                        'msg' => '商品更新成功'
                    ];

                }else {
                    $data = [
                        'status' => 0,
                        'msg' => '商品更新失败'
                    ];

                }
            return json($data);
            }
        }
    //单个商品的删除
    public function postDelete($id){
        $goods=\app\admin\model\Goods::get($id);
        //查到商品图片的链接
        $path=$goods->where('id',$id)->value('goods_pic');
        $path='./uploads/'.$path;
        if ($goods->delete()){
            //表删除成功就删除图片
            if (file_exists($path) && is_file($path)) {
                //存在就删除图片
                if (unlink($path)) {
                    // 删除关联的商品颜色
                    $goods->profile->delete();
                    $data = [
                        'status' => 0,
                        'msg' => '商品删除成功'
                    ];
                } else {
                    // 删除关联的商品颜色
                    $goods->profile->delete();
                    $data = [
                        'status' => 1,
                        'msg' => '文件删除失败'
                    ];
                }
            } else {
                // 删除关联的商品颜色
                $goods->profile->delete();
                $data = [
                    'status' => 2,
                    'msg' => '文件不存在'
                ];
            }
        } else {
            $data = [
                'status' => 3,
                'msg' => '出现未知错误'
            ];
        }
            return json($data);
    }
    //多商品的批量删除
    public function postDatadel(){
        //商品的删除
        $ids=explode(',',input('post.')['ids']);
        //获取每条数据的图片path
        // 启动事务
        Db::startTrans();
        $paths=array();
        try {
            foreach ($ids as $id){
                $goods = \app\admin\model\Goods::get($id);
                //查到商品图片的链接
                $path = $goods->where('id', $id)->value('goods_pic');
                $path = './uploads/' . $path;
                array_push($paths, $path);
                if (!$goods->delete()){
                    throw new Exception("商品数据删除失败");
                }
                //表删除成功就删除颜色表
                if(!$goods->profile->delete()){
                    throw new Exception("商品颜色删除失败");
                }
            }
            // 提交事务
            Db::commit();
        }catch (Exception $e){
            //回滚事务
            Db::rollback();

        }
        /*//商品的删除
        $ids=explode(',',input('post.')['ids']);
        //获取每条数据的图片path
        $paths=array();
        foreach ($ids as $id){
            $goods=\app\admin\model\Goods::get($id);
            //查到商品图片的链接
            $path=$goods->where('id',$id)->value('goods_pic');
            $path='./uploads/'.$path;
            array_push($paths,$path);
            if ($goods->delete()){
                //表删除成功就删除颜色表
                $goods->profile->delete();
                //表删除成功就删除图片
                if (file_exists($path) && is_file($path)){
                    //存在就删除图片
                    if (unlink($path)) {
                        $data = [
                            'status' => 0,
                            'msg' => '商品删除成功'
                        ];
                    } else {
                        $data = [
                            'status' => 1,
                            'msg' => '文件删除失败'
                        ];
                    }
                } else {
                    $data = [
                        'status' => 2,
                        'msg' => '文件不存在'
                    ];
                }
            } else {
                $data = [
                    'status' => 3,
                    'msg' => '出现未知错误'
                ];
                return json($data);
            }

        }*/
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