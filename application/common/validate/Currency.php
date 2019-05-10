<?php

namespace app\common\validate;

use think\Validate;

class Currency extends Validate
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
        'code' => [
            'max:2',
        ],
        'name' => [
            'max:20',
        ],
        'desc' => [
            'max:50',
        ],
        'exchange_rate' => [
            'max:4',
            'number',
        ],
        'gst_rate' => [
            'max:4',
            'number',
        ],
        'fees_gst_rate' => [
            'max:4',
            'number',
        ],
        'exchange_loss' => [
            'max:10',
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