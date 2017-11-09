<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    //
    protected $table = 'tp_admin';

    //状态值
    public function getStatusAttr($value)
    {
        $status = [1=>'隐藏',0=>'显示'];

        return $status[$value];
    }
}
