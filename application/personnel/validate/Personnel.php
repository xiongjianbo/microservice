<?php

namespace app\personnel\validate;

use think\Validate;

class Personnel extends Validate
{
    protected $rule = [
        'id' => 'require|integer|max:11',
        'name' => 'max:30',
        'type' => 'integer|isType',
        'username' => 'max:30|unique:personnel',
        'position_id' => 'integer|max:11',
        'department_id' => 'integer|max:11',
        'number' => 'integer|max:8|unique:personnel',
        'sex' => 'length:1|integer|isSex',
        'birthday' => 'integer|max:10',
        'phone' => 'max:14',
        'education' => 'isEducation',
        'graduate_school' => 'max:30',
        'major' => 'max:30',
        'home_place' => 'max:30',
        'home_post' => 'max:6',
        'address' => 'max:30',
        'current_post' => 'max:6',
        'contact' => 'max:30',
        'contact_phone' => 'max:14',
        'contact_role' => 'max:10',
        'contact_address' => 'max:30',
        'contact_post' => 'max:6',

    ];

    protected $scene = [
        'show' => ['id'],
        'update' => [],
        'destroy' => ['id'],
    ];

    // 验证场景定义
    public function sceneStore()
    {
        return $this->append('name', 'require')
            ->append('username', 'require')
            ->append('position_id', 'require')
            ->append('department_id', 'require')
            ->remove('id');
    }
    public function sceneUpdateSelf()
    {
        return $this
            ->remove('id');
    }
    public function sceneIndex()
    {
        return $this->only(['name', 'department_id', 'type'])
            ->append('type', 'require|array|isTypeArr')
        ->remove('type', 'integer|isType');
    }

    protected function isEducation($string)
    {
        return in_array($string, ['博士', '研究生', '硕士', '本科', '大专', '高中', '其他']);
    }

    protected function isSex($string)
    {
        return in_array($string, [1, 2]);
    }

    protected function isType($string)
    {
        return in_array($string, [1, 2, -1, -2]);
    }

    protected function isTypeArr($arr)
    {
        foreach ($arr as $v) {
            if (!$this->isType($v)) {
                return false;
            }
        }
        return true;
    }

}
