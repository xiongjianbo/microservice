<?php

namespace app\common\validate;

use think\Validate;

class PersonalSettingManage extends Validate
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
        'is_welcome' => [
            'max:1',
            'number',
        ],
        'is_news' => [
            'max:1',
            'number',
        ],
        'is_task' => [
            'max:1',
            'number',
        ],
        'is_schedule' => [
            'max:1',
            'number',
        ],
        'is_open' => [
            'max:1',
            'number',
        ],
        'secondary_password' => [
            'max:32',
        ],
        'personnel_id' => [
            'max:11',
            'number',
        ],
        'delete_time' => [
            'date',
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
        'add' => ['id','is_welcome'],
        'edit' => ['id'],
    ];
}