<?php
namespace  app\home\widget;
use think\Controller;
use think\Db;

class Cate extends Controller
{
    public function getcatebypid($pid)
    {
        $cate=Db::table('tp_category')->where('pid',$pid)->select();
        $datas=[];
        //遍历
        foreach ($cate as $key=>$value)
        {
            //shop下标用来存储当前子类信息
            $value['shop']=$this->getcatebypid($value['id']);
            $datas[]=$value;
        }
        return $datas;
    }

    public function header()
    {
        $cate=$this->getcatebypid(0);
       return $this->fetch("public:header",['cate'=>$cate]);
    }

    public function footer()
    {
      return $this->fetch('public:footer');
    }
}
?>