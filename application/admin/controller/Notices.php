<?php
namespace app\admin\controller;

use app\admin\AdminCommon;
use think\Db;
use think\Request;

class Notices extends AdminCommon
{
    //读取公告列表
    public function getindex()
    {
         //$user = new \app\admin\model\Notices();
        // $data=$user->all();
         $data=Db::query('select * from tp_notices');
        //$this->assign('data',$data);
         $i=0;
        foreach($data as $v)
        {
            $data[$i]['create_time']=date('Y-m-d',$v['create_time']);
            $data[$i]['update_time']=date('Y-m-d',$v['update_time']);
            $i++;
         }
        return $this->fetch('notices/index',['data'=>$data,'b'=>1]);
    }

    //新增公告
    public function getadd()
    {
        return $this->fetch('notices/create');
    }

    //处理添加公告
    public function postdoadd(Request $request)
    {
        $res=$request->except(['action']);
        $create_time=time();
        $update_time=time();
        $res['create_time']=$create_time;
        $res['update_time']=$update_time;
        $result=Db::table('tp_notices')->insert($res);
        if($result){
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

    //修改公告
    public function getedit()
    {
        $id=$_GET['id'];
        $data=Db::table('tp_notices')->where('id',$id)->find();
        return $this->fetch('notices/edit',['data'=>$data]);
    }

    //处理修改公告
    public function postdoedit(Request $request)
    {
        $res=$request->param();
        $create_time=time();
        $update_time=time();
        $res['create_time']=$create_time;
        $res['update_time']=$update_time;
        unset($res['action']);
        $result=Db::table('tp_notices')->where('id',$res['id'])->update($res);
        if($result) {
            //判断插入是否成功
            $data=[
                'status'=>1,
                'msg'=>'修改成功',
            ];
        }else {
            $data=[
                'status'=>0,
                'msg' => '修改',
                ];
        };
        return $data;
    }

    //删除公告
    public function postdel(Request $request)
    {
        $res=$request->param();
        unset($res['action']);
        $result=Db::table('tp_notices')->where('id',$res['id'])->delete();
        if($result){
            $data=[
                'status'=>1,
                 'msg'=>'删除成功',
            ];
        }else{
            $data=[
                'status'=>0,
                'msg'=>'删除失败',
            ];
        }
        return $data;
    }

    //公告详情
    public function getdetail()
    {
        $data=Db::table('tp_notices')->where('id',$_GET['id'])->find();
        return $this->fetch('notices/detail',['data'=>$data]);
    }

    //禁用
    public function postnotice_stop()
    {
          $request=$this->request;
          $res=$request->param();
          $state=1;
          $res['state']=$state;
          unset($res['action']);
          $result=Db::table('tp_notices')->where('id',$res['id'])->update(['state'=>'1']);
          if($result) {
            $data=[
                'status'=>1,
                'msg'=>'禁用',
                ];
          }else{
              $data=[
                  'status'=>0,
                  'msg'=>'启用',
              ];
          }
          return $data;
    }

    //启用
    public function postnotice_start()
    {
        $request=$this->request;
        $res=$request->param();
        $state=0;
        $res['state']=$state;
        unset($res['action']);
        $result=Db::table('tp_notices')->where('id',$res['id'])->update(['state'=>'0']);
        if($result) {
            $data=[
                'status'=>1,
                'msg'=>'启用',
            ];
        }else{
            $data=[
                'status'=>0,
                'msg'=>'禁用',
            ];
        }
        return $data;
    }
}
?>