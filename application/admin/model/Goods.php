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
}