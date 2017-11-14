<?php

namespace app\admin\controller;

use think\Controller;
use think\Hook;
use think\Request;
use think\console\Input;
//use app\admin\model\Category;
class Category extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $Category=new \app\admin\model\Category();
        $data=$Category->tree();
        $arr=$Category->where('pid',0)->select();
        $this->assign('data',$data);
        $this->assign('arr',$arr);
        return $this->fetch('category/index');

    }

    /*
     * 搜索列表
     * */
    public function search(Request $request){
        $Category=new \app\admin\model\Category();
        $arr=$Category->where('pid',0)->select();
        $pid=$request->request('pid');
        $search=$request->request('search');
        $this->assign('arr',$arr);
        //表示搜索的顶级分类的时候就会进行下面的搜索
        if($pid=='a'){
            //直接点击的搜索
            if($search==''){
                return $this->index();
            }
            //查询到搜索目录的信息
            $data=$Category->where('catname',$search)->select();
            if(empty($data)){
                $this->error('分类名称不存在，请重新搜索','category/index','','2');
            }
            //通过遍历目录得到pid，再得到父级目录的catname
            foreach ($data as $k=>$v){
                $fjpid=$v['pid'];
                $data2=$Category->where('id',$fjpid)->select();
                //通过得到的父级的信息得到父级名字
                foreach ($data2 as $k=>$val){
                    $fjcatname=$val['catname'];
                    //得到的多个的父级信息组合成功数组，并且数组下标设置为父级的id，也就子类的pid
                    $id=$val['id'];
                    $data3[$id]=$fjcatname;
                    //这样子传过去就可以得到父级的信息
                    $this->assign('data3',$data3);
                }
            }
            //只有得到一个柴
            $this->assign('data',$data);
            return $this->fetch('category/search');

        }
        //当选择的分类的时候
        if(empty($search)){
            $data=$Category->where('pid',$pid)->select();
        }else{
            $map['catname']=$search;
            $map['pid']=$pid;
            $data=$Category->where($map)->select();

        }
        //得到选择的父级信息
        $data2=$Category->where('id',$pid)->select();
        foreach ($data2 as $val){
            $fjcatname=$val['catname'];
            $id=$val['id'];
            $data3[$id]=$fjcatname;
            $this->assign('data3',$data3);
        }

        $this->assign('data',$data);
        return $this->fetch('category/search');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $Category=new \app\admin\model\Category();
        $data=$Category->where('pid',0)->select();
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
        $Category= \app\admin\model\Category::create($data);
        if($Category){
            $this->success('添加成功','index');
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
        $Category=\app\admin\model\Category::get($id);
        $Category->catorder=$data['catorder'];
        $re=$Category->save();
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
        //显示顶级分类
        $Category=new \app\admin\model\Category();
        $arr=$Category->tree();
        //arr是顶级分类名称
        $arr=$Category->where('pid',0)->select();
        //需要得到的是
        //显示编辑商品的详情
        $data=$Category->where('id',$id)->select();
        $this->assign('data',$data);

        $this->assign('arr',$arr);
        //采用完整路径去调用模板
        return view();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {

        $data1=$request->except(['__token__','id']);
        $id=$request->only('id');
        $ids=$id['id'];
        $Category=new \app\admin\model\Category();
        //通过传来的id进行判断是不是顶级栏目，再判断下面有没有子分类，如果有子分类，就不能够进行修改。如果没有，就可以修改
        //查询是否存在子分类,查询得到的是子分类的信息
        $su=$Category->where('pid',$ids)->select();
        //当查询出来su有值，就表示是父类，并且有子分类
        $cs_pid=$data1['pid'];
        //如果有结果,并且选择的新分类不是顶级分类 。就说明不能进行修改
        if((!empty($su))&&($cs_pid!=0)){
            $this->error('当前分类下有子目录，无法修改目录位置');
        }
        $re=$Category->where($id)->update($data1);
        if($re){
            $this->success('数据修改成功！！','index','','1');
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
        $Category=new \app\admin\model\Category();
        //先要查询是不是父级目录，父级目录不能够直接删除
        $re=$Category->where('pid',$id)->select();
        if($re==null){
            $Category->where('id',$id)->delete();
            //状态为零，表示删除成功
            $data['static'] = 0;
        }else{
            //状态为1，表示删除失败
            $data=
                [
                    'static' => 1,
                    'msg' => '目录下存在子分类，无法删除',
                ];
        };

        return $data;
    }
}
