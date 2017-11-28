<?php
namespace app\admin\model;
use think\Model;

class Goods extends Model
{
    protected $table = 'tp_goods';
    //设置时间类型
    protected $autoWriteTimestamp = true;
    // 定义关联方法
    public function profile()
    {
        // 用户HAS ONE档案关联
        return $this->hasOne('GoodsColor','goods_id','id');
    }
    //获取器修改显示内容  状态
    public function getGoodsStatusAttr($value)
    {
        $status = [1=>'下架',0=>'上架'];
        return $status[$value];
    }
    public function getGoodsUnitAttr($value)
    {
        $unit = [
            1=>'件',
            2=>'斤',
            3=>'KG',
            4=>'吨',
            5=>'套',
        ];
        return $unit[$value];
    }
}