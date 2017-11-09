<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    //
    protected $table = 'tp_admin';
    //设置时间类型
    protected $autoWriteTimestamp = true;
    //状态值
    public function getStatusAttr($value)
    {
        $status = [1=>'已禁用',0=>'已启用'];

        return $status[$value];
    }
    //栏目名称
    public function getAdminroleAttr($value)
    {
        $Adminrole = [
            0=>'超级管理员',
            1=>'总编',
            2=>'栏目主辑',
            3=>'栏目编辑',

        ];

        return $Adminrole[$value];
    }
}
