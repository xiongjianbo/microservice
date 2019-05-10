<?php

namespace app\personnel\validate;

use think\Validate;

class SupplementPurchase extends Validate
{
    protected $rule = [
        'name' => 'require|between:1,2',
        'parameter' => 'require',
        'number' => 'integer',
        'unit' => 'require',
        'price' => 'require',
        'price_unit' => 'require',
        'supplier_id' => 'require|integer|max:11',
        'reason' => 'require',
        'verify_content' => 'require',
        'verify_status' => 'require|isCorrect',
    ];

    protected $scene = [
        'store' => ['name', 'parameter', 'number', 'unit', 'price', 'price_unit', 'supplier_id', 'reason'],
        'index' => ['personnel_id'],
        'show' => ['id'],
        'wait' => ['current_status'],
        'update' => ['id', 'verify_status', 'verify_content'],
    ];

    // 验证审批结果是否正确 1通过,-1不通过
    protected function isCorrect($status)
    {
        if ($status == -1 || $status == 1) {
            return true;
        }
        return false;
    }
}
