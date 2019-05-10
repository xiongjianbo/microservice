<?php

namespace app\common\validate;

use think\Validate;

class SalaryLevel extends Validate
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
        'position_id' => [
            'max:32',
            'require'
        ],
        'name' => [
            'max:32',
            'require'
        ],
        'level' => [
            'max:11',
            'number',
        ],
        'bonus' => [
            'max:11',
            'number',
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
        'edit' => ['name','level','bonus'],
    ];
}