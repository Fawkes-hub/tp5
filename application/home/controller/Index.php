<?php
namespace app\home\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $notice_datas=Db::table('tp_notices')->limit(4)->order('create_time DESC')->select();
        $i=0;
        foreach($notice_datas as $v)
        {
            $notice_datas[$i]['create_time']=date('Y-m-d',$v['create_time']);
            $notice_datas[$i]['update_time']=date('Y-m-d',$v['update_time']);
            $i++;
        }

        $adversts_datas=Db::table('tp_adversts')->order('id DESC')->select();
        $j=0;
        foreach($adversts_datas as $v)
        {
            $adversts_datas[$j]['pic']='/uploads/adversts_pic/'.$v['pic'];
            $j++;
        }
        $this->assign('adversts_datas',$adversts_datas);
        $this->assign('notice_datas',$notice_datas);
        return $this->fetch('index/index');
    }


}
