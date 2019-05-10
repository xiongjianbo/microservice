<?php

namespace app\personnel\validate;

use think\Validate;

class Position extends Validate
{
    protected $rule = [
        'id' => 'require|integer|max:11',
        'department_id' => 'require|integer|max:11',
        'title' => 'require|max:30',
    ];

    protected $message = [
    ];

    protected $scene = [
        'rule' => '',
        'store' => ['department_id', 'title'],
        'show' => ['id'],
        'destroy' => ['id'],
    ];

    // 验证场景定义
    public function sceneUpdate()
    {
        return $this->remove('p_id', 'require');
    }
}
