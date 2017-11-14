<?php

namespace app\admin\model;

use think\Model;

class Login extends Model
{
    //设置连接的数据库
    protected $table = 'tp_admin';

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