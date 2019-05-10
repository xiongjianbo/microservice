<?php

namespace app\common\validate;

use think\Validate;

class Task extends Validate
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
        'p_id' => [
            'max:11',
            'number',
        ],
        'project_id' => [
            'max:11',
            'number',
        ],
        'sub_project_id' => [
            'max:11',
            'number',
        ],
        'task_number' => [
            'max:20',
        ],
        'name' => [
            'max:50',
        ],
        'status' => [
            'max:1',
            'number',
        ],
        'type' => [
            'max:20',
        ],
        'style' => [
            'max:1',
            'number',
        ],
        'way' => [
            'max:1',
            'number',
        ],
        'leave' => [
            'max:1',
            'number',
        ],
        'performance_day' => [
            'max:3',
            'number',
        ],
        'price' => [
            'max:10',
            'number',
        ],
        'price_unit' => [
            'max:3',
        ],
        'balance_day' => [
            'date',
        ],
        'attachment_uri' => [
        ],
        'range' => [
        ],
        'description' => [
        ],
        'personnel_id' => [
            'max:11',
            'number',
        ],
        'schedule' => [
            'max:3',
            'number',
        ],
        'company_id' => [
            'max:11',
            'number',
        ],
        'expect_start_time' => [
            'date',
        ],
        'expect_finish_time' => [
            'date',
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
        'add' => ['project_id'],
        'edit' => ['id'],
    ];
}