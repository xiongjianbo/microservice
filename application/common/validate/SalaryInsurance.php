<?php

namespace app\common\validate;

use think\Validate;

class SalaryInsurance extends Validate
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
        'company_id' => [
            'max:11',
            'number',
        ],
        'name' => [
            'max:32',
            'require'
        ],
        'type' => [
            'max:11',
            'number',
            'require'
        ],
        'personal' => [
            'max:11',
            'number',
        ],
        'company' => [
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
        'add' => ['name', 'type', 'personal', 'company'],
        'edit' => ['id'],
    ];
}