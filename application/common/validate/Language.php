<?php

namespace app\common\validate;

use think\Validate;

class Language extends Validate
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
        'company_id' => [
            'require',
            'max:11',
            'number',
        ],
        'chinese' => [
            'in:0,1',
        ],
        'english' => [
            'in:0,1',

        ],
        'japanese' => [
            'in:0,1',
        ],
        'create_time' => [
            'date',
        ],
        'update_time' => [
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
        'add' => ['company_id','chinese','english','japanese'],
        'edit' => ['id','chinese','english','japanese'],
    ];
}