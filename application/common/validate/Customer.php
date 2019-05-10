<?php

namespace app\common\validate;

use think\Validate;

class Customer extends Validate
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
        'name' => [
            'max:50',
        ],
        'abbreviation' => [
            'max:30',
        ],
        'address' => [
            'max:150',
        ],
        'type' => [
            'max:50',
        ],
        'tel' => [
            'max:20',
        ],
        'email' => [
            'max:50',
            'email',
        ],
        'business' => [
            'max:100',
        ],
        'contact' => [
        ],
        'statement_date' => [
            'max:2',
            'number',
        ],
        'company_id' => [
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
        'add' => ['name'],
        'edit' => ['id'],
    ];
}