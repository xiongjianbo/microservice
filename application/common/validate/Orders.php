<?php

namespace app\common\validate;

use think\Validate;

class Orders extends Validate
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
        'number' => [
            'max:32',
        ],
        'company_id' => [
            'max:11',
            'number',
            'require',
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
        'expect_date' => [
            'max:11',
            'number',
        ],
        'expect_money' => [
            'max:32',
        ],
        'currency_id' => [
            'max:11',
            'number',
        ],
        'personnel_id' => [
            'max:11',
            'number',
        ],
        'pay_type' => [
            'max:11',
            'number',
        ],
        'explains' => [
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
        'save' => ['number','company_id','customer_id','project_type_id','name','expect_date','expect_money'],
        'edit' => ['id'],
    ];
}