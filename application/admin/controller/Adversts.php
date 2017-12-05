<?php
namespace  App\admin\controller;

use app\admin\AdminCommon;
use think\Db;

class Adversts extends AdminCommon
{
    //显示广告列表
    public function getindex()
    {
        $res=Db::table('tp_adversts')->select();
        $data='';
        $i=0;
        foreach($res as $val)
        {
            if($val['end_time']==null) {
                $val['end_time']=0;
                $val['time_state']='0';

            }else {
                $currenttime=time();
                if($currenttime<$val['create_time'])
                {
                    $val['time_state']='2';
                    $end_time=date('Y-m-d',$val['end_time']);
                    $val['end_time']=$end_time;
                }else{
                    if($currenttime>=$val['create_time'] && $currenttime<=$val['end_time']){
                        $savetime=$val['end_time']-$val['create_time'];
                        $savetime=$savetime/(60*60*24);
                        $val['savetime']=$savetime.'天';
                        $end_time=date('Y-m-d',$val['end_time']);
                        $val['end_time']=$end_time;
                        $val['time_state']='1';
                    }else{
                        if($currenttime>$val['end_time']){
                            $val['time_state']='3';
                            $end_time=date('Y-m-d',$val['end_time']);
                            $val['end_time']=$end_time;
                            Db::table('tp_adversts')->where('id',$val['id'])->update(['state'=>1]);
                        }
                    }
                }

            }
                $create_time=date('Y-m-d',$val['create_time']);
                $val['create_time']=$create_time;
                $pic='/uploads/adversts_pic/'.$val['pic'];
                $val['pic']=$pic;
                $data[$i]=($val);
                $i++;
            }

       // var_dump($data);
        return $this->fetch('/adversts/index',['data'=>$data,'b'=>1]);
    }

    //添加广告（加载广告模板）
    public function getadd()
    {
        return $this->fetch('adversts/create');
    }

    //处理添加广告模板
    public function postdoadd()
    {
        $request=$this->request;
        $res=$request->param();
        unset($res['action']);
        unset($res['file']);
        if($res['create_time'])
        {
            $create_time=strtotime($res['create_time']);
            $res['create_time']=$create_time;
        }
        if($res['end_time']){
            $end_time=strtotime($res['end_time']);
            $res['end_time']=$end_time;
        }
        $result=Db::table('tp_adversts')->insert($res);
        if($result) {
            //判断插入是否成功
            $data=[
                'status'=>1,
                'msg'=>'添加成功',
            ];
        }else {
            $data=[
                'status'=>0,
                'msg' => '添加失败',
            ];
        };
        return $data;
    }

    //图片上传
    public function postpics()
    {
        $files = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($files)
        {
            $info = $files->validate(['size'=>8112392,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads/adversts_pic');

            if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $pic=$info->getSaveName();
                return json(array('status'=>1,'path'=>$pic,'msg'=>'图片上传成功'));
            }else{
                // 上传失败获取错误信息
                echo $info->getError();
                return json(array('status'=>0,'msg'=>'上传失败'));
            }
        }
    }

    public function getedit()
    {
        $request=$this->request;
        $res=$request->param();
        unset($res['action']);
        $data=Db::table('tp_adversts')->where('id',$res['id'])->find();
        $data['create_time']=date('Y-m-d',$data['create_time']);
        $data['end_time']=date('Y-m-d',$data['end_time']);
        return $this->fetch('/adversts/edit',['data'=>$data]);
    }

    public function postdoedit()
    {
        $request=$this->request;
        $res=$request->param();
        $old_res=Db::table('tp_adversts')->where('id',$res['id'])->find();
        $old_pic=$old_res['pic'];
        unset($res['action']);
        unset($res['file']);
        if($res['create_time'])
        {
            $create_time=strtotime($res['create_time']);
            $res['create_time']=$create_time;
        }
        if($res['end_time']){
            $end_time=strtotime($res['end_time']);
            $res['end_time']=$end_time;
        }

        //判断他没有上传新的图片
        if($res['pic']==$old_pic)
        {
            $result=Db::table('tp_adversts')->where('id',$res['id'])->update($res);
            if($result) {
                //判断插入是否成功
                $data=[
                    'status'=>1,
                    'msg'=>'修改成功',
                ];
            }else {
                $data=[
                    'status'=>0,
                    'msg' => '修改失败',
                ];
            };
        }else{
         //有图片上传
            $path='./uploads/adversts_pic/'.$old_pic;
            unlink($path);
            $result=Db::table('tp_adversts')->where('id',$res['id'])->update($res);
            if($result) {
                //判断插入是否成功
                $data=[
                    'status'=>1,
                    'msg'=>'修改成功',
                ];
            }else {
                $data=[
                    'status'=>0,
                    'msg' => '修改失败',
                ];
            };
        }
        return $data;
    }

    //广告删除
    public function postdelete()
    {
      $request=$this->request;
      $res=$request->param();
      $new_res=Db::table('tp_adversts')->where('id',$res['id'])->find();
      $pathinfoss=$new_res['pic'];
      $pathinfoes='./uploads/adversts_pic/'.$pathinfoss;
      unlink($pathinfoes);
      $paths=pathinfo($pathinfoes,PATHINFO_DIRNAME);
      if(count(scandir($paths))==2)
      {
          rmdir($paths);
      }
      $result=Db::table('tp_adversts')->where('id',$res['id'])->delete();
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

    //广告停用
    public function poststop()
    {
      $request=$this->request;
      $res=$request->param();
      $state=1;
      $res['state']=$state;
      unset($res['action']);
      $result=Db::table('tp_adversts')->where('id',$res['id'])->update(['state'=>'1']);
      return $res['id'];
    }

    //广告启用
    public function poststart()
    {
        $request=$this->request;
        $res=$request->param();
        $state=0;
        $res['state']=$state;
        unset($res['action']);
        $result=Db::table('tp_adversts')->where('id',$res['id'])->update(['state'=>'0']);
        return $res['id'];
    }

    //广告批量删除
    public function postdatadel()
    {
        $request=$this->request;
        $res=$request->param();
        $ids=explode(',',$res['ids']);
        $paths=array();
        Db::startTrans();
        try{
            foreach ($ids as $id)
            {
                $ress=Db::table('tp_adversts')->where('id',$id)->find();
                //新建数组，用于储存图片
                $pathes='./uploads/adversts_pic/'.$ress['pic'];
                array_push($paths,$pathes);
                $result=Db::table('tp_adversts')->where('id',$id)->delete();
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        if($result)
        {
           foreach ($paths as $pathinfos)
           {
               unlink($pathinfos);
               $pathess=pathinfo($pathinfos,PATHINFO_DIRNAME);
               if(count(scandir($pathess))==2)
               {
                   rmdir($pathess);
               }

           }
            $data=[
                'status'=>1,
                'msg'=>'批量删除成功',
            ];
        }else{
            $data=[
                'status'=>0,
                'msg'=>'批量删除失败',
            ];
        }
      return $data;
    }
}
?>