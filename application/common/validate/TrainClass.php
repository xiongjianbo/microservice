<?php

namespace app\common\validate;

use think\Validate;

class TrainClass extends Validate
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
        'company_id' => [
            'max:11',
            'number',
        ],
        'name' => [
            'max:255',
        ],
        'begin_time' => [
            'date',
        ],
        'teacher' => [
            'max:255',
        ],
        'address' => [
            'max:255',
        ],
        'target' => [
        ],
        'join_type' => [
        ],
        'time_length' => [
            'max:20',
        ],
        'most_personnel' => [
            'max:6',
            'number',
        ],
        'current_status' => [
        ],
        'description' => [
        ],
        'create_time' => [
            'date',
        ],
        'update_time' => [
            'date',
        ],
        'delete_time' => [
            'date',
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
        'add' => [],
        'edit' => ['id'],
    ];
}