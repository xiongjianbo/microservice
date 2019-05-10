<?php

namespace app\common\validate;

use think\Validate;

class Projects extends Validate
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
        'order_id' => [
            'max:32',
        ],
        'company_id' => [
            'max:11',
            'number',
        ],
        'customer_id' => [
            'max:11',
            'number',
        ],
        'project_type_id' => [
            'max:11',
            'number',
        ],
        'name' => [
            'max:32',
        ],
        'done_date' => [
            'max:11',
            'number',
        ],
        'begin_date' => [
            'max:11',
            'number',
        ],
        'expect_date' => [
            'max:11',
            'number',
        ],
        'expect_money' => [
            'max:32',
        ],
        'currency_id' => [
            'max:11',
        ],
        'personnel_id' => [
            'max:11',
            'number',
        ],
        'instructions' => [
        ],
        'status' => [
            'max:11',
            'number',
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
        'save' => ['id','order_id','company_id','customer_id','project_type_id','name','done_date','begin_date','expect_date','expect_money','currency_id','personnel_id'],
    ];
}