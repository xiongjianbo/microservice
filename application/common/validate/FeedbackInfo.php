<?php

namespace app\common\validate;

use think\Validate;

class FeedbackInfo extends Validate
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
        'feedback_id' => [
            'max:11',
            'number',
        ],
        'content' => [
            'max:500',
        ],
        'translate' => [
            'max:500',
        ],
        'feedback_categories' => [
            'max:16',
        ],
        'reason' => [
            'max:1',
            'number',
        ],
        'personnel_id' => [
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
        'edit' => ['id'],
    ];
}