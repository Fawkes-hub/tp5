<?php
namespace app\home\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $notice_datas=Db::table('tp_notices')->select();
        //var_dump($notice_datas);
        $i=0;
        foreach($notice_datas as $v)
        {
            $notice_datas[$i]['create_time']=date('Y-m-d',$v['create_time']);
            $notice_datas[$i]['update_time']=date('Y-m-d',$v['update_time']);
            $i++;
        }
        $this->assign('notice_datas',$notice_datas);
        return $this->fetch('index/index');
    }


}
