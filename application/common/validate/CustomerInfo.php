<?php

namespace app\common\validate;

use think\Validate;

class CustomerInfo extends Validate
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
        'customer_id' => [
            'max:10',
            'number',
        ],
        'is_default' => [
            'max:4',
            'number',
        ],
        'contact_person' => [
            'max:30',
        ],
        'telephone' => [
            'max:16',
        ],
        'skype' => [
            'max:50',
        ],
        'email' => [
            'max:120',
            'email',
        ],
        'other_contact_info' => [
            'max:120',
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
        'add' => ['customer_id','is_default','contact_person','telephone','skype','email','other_contact_info'],
        'edit' => ['customer_id','is_default','contact_person','telephone','skype','email','other_contact_info'],
    ];
}