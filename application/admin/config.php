
<?php
/**
 * Created by PhpStorm.
 * User: 冯轲
 * Date: 2017-11-30
 * Time: 11:23
 */

return [
    //设置模板布局
    'template'  =>  [
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ],
    // 默认跳转页面对应的模板文件
    /* 'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
     'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',*/
    'dispatch_success_tmpl'  => 'public/admin_jump',
    'dispatch_error_tmpl'    => 'public/admin_jump',
]
?>