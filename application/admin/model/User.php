<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $table = 'tp_user';
    //设置时间类型
    protected $autoWriteTimestamp = true;
    //状态值
    public function getStatusAttr($value)
    {
        $status = [1=>'已禁用',0=>'已启用'];

        return $status[$value];
    }
    //状态值
    public function getUserSexAttr($value)
    {
        $status = [0=>'女',1=>'男'];

        return $status[$value];
    }
}
