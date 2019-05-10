<?php

namespace app\personnel\validate;

use think\Validate;

class SupplementSign extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'type' => 'require|between:1,2',
        'day' => 'require|dateFormat:Y-m-d',
        'sign_time' => 'require|dateFormat:H:i:s',
        'reason' => 'require',
        'attachment_uri' => 'array',
        'verify_content' => 'require',
        'verify_status' => 'require|isCorrect',
        'current_status' => 'require|between:-1,1',
        'personnel_id' => 'integer',
        'department_id' => 'integer',
        'id' => 'integer|require',
    ];


    protected $scene = [
        'store' => ['type', 'day', 'sign_time', 'reason', 'attachment_uri'],
        'index' => ['personnel_id', 'department_id'],
        'show' => ['id'],
        'wait' => ['current_status'],
        'update' => ['id', 'verify_status', 'verify_content'],
    ];

    // 判断指定的键名是否存在
    public function isCorrectTimeLen($array)
    {

        foreach ($array as $item) {
            if (!(array_key_exists('day', $item)
                && array_key_exists('hour', $item))
            ) {
                return false;
            }

        }
        return true;
    }

    // 验证审批结果是否正确 1通过,-1不通过
    protected function isCorrect($status)
    {
        if ($status == -1 || $status == 1) {
            return true;
        }
        return false;
    }
}
