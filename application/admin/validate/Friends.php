<?php
    namespace  app\admin\validate;
    use think\Validate;
    class Friends extends  Validate
    {
        protected $rule = [
            'firends_name'=>'require|max:10',
            'firends_path'=>'require|unique:firends',
            'firends_status'=>'require',
    ];
        protected $message  =[
            'firends_name.require'=>'标题必须填写',
            'firends_name.max'=>'标题最多数字为10',
           'firends_path.require'=>'路径必须填写',
            'firends_path.unique'=>'已添加过',
            'firends_status.require'=>'状态必须填写',

        ];
    }