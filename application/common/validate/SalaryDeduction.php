<?php

namespace app\common\validate;

use think\Validate;

class SalaryDeduction extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => [
            'max:11',
            'number',
        ],
        'insurance' => [
            'max:11',
            'number',
        ],
        'insurance_type' => [
            'max:11',
            'number',
        ],
        'late' => [
            'max:11',
            'number',
        ],
        'late_type' => [
            'max:11',
            'number',
        ],
        'early' => [
            'max:11',
            'number',
        ],
        'early_type' => [
            'max:11',
            'number',
        ],
        'thing_leave' => [
            'max:11',
            'number',
        ],
        'thing_leave_type' => [
            'max:11',
            'number',
        ],
        'sick_leave' => [
            'max:11',
            'number',
        ],
        'sick_leave_type' => [
            'max:11',
            'number',
        ],
        'company_id' => [
            'max:11',
            'number',
        ],
        'plan_id' => [
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
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
//        'id.require' => 'ID必须输入',
    ];

    /**
     * 验证场景
     * 格式：'场景名'    =>    ['字段1','字段2']
     * @var array
     */
    protected $scene = [
        'add' => [],
        'edit' => ['id'],
    ];
}