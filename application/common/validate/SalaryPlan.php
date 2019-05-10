<?php

namespace app\common\validate;

use think\Validate;

class SalaryPlan extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => [
            'max:11',
            'number',
        ],
        'type' => [
            'max:11',
            'number',
        ],
        'name' => [
            'max:128',
        ],
        'create_time' => [
        ],
        'update_time' => [
        ],
        'delete_time' => [
        ],
        'company_id' => [
            'max:11',
            'number',
        ],
        'apply' => [
            'max:11',
            'number',
        ],
        'apply_id' => [
            'max:11',
            'number',
        ],

    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
//        'id.require' => 'ID必须输入',
    ];

    /**
     * 验证场景
     * 格式：'场景名'	=>	['字段1','字段2']
     * @var array
     */
    protected $scene = [
        'add' => ['type','name','apply','apply_id'],
        'edit' => ['id'],
    ];
}