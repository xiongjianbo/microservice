<?php

namespace app\personnel\validate;

use think\Validate;

class Department extends Validate
{
    protected $rule = [
        'id' => 'require|integer|max:11',
        'p_id' => 'require|integer|max:11',
        'name' => 'require|max:30',
    ];

    protected $message = [
    ];

    protected $scene = [
        'store' => ['p_id', 'name'],
        'destroy' => ['id'],
    ];

    // 验证场景定义
    public function sceneUpdate()
    {
        return $this->remove('p_id', 'require');
    }
}
