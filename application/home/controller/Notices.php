<?php
/**
 * Created by PhpStorm.
 * User: 冯轲
 * Date: 2017-12-4
 * Time: 8:54
 */
namespace app\home\controller;

use app\admin\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use ucpaas\Ucpaas;

class Notices extends Controller
{
    public function getIndex(){
        $request=$this->request;
        $res=$request->param();
        $data=Db::table('tp_notices')->where('id',$res['id'])->find();
        $data['create_time']=date('Y-m-d',$data['create_time']);
        return $this->fetch('notices/notices_detail',['data'=>$data]);
    }
}