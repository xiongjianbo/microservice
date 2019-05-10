<?php

namespace app\common\validate;

use think\Validate;

class ProjectDelivery extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'id' => [
            'require',
            'max:11',
            'number',
        ],
        'project_id' => [
            'require',
            'max:11',
            'number',
        ],
        'is_collection' => [
            'in:0,1',
        ],
        'comment' => [
            'max:255',
        ],
        'status'=>[
            'in:1,2'
        ]
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];


    /**
     * 验证场景
     * 格式：'场景名'	=>	['字段1','字段2']
     * @var array
     */
    protected $scene = [
        'add' => ['project_id'],
        'edit' => ['id','project_id'],
        'audit'=>['id','status']
    ];
}
