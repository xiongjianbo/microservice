<?php

namespace app\asset\model;

use think\Model;
use think\model\concern\SoftDelete;

class SupplementPurchase extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    /**
     * 获取关联的供应商
     *
     * @return \think\model\relation\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('Supplier');
    }

    /**
     * 获取关联的供应商
     *
     * @return \think\model\relation\BelongsTo
     */
    public function personnel()
    {
        return $this->belongsTo('app\common\model\Personnel')->field('id,name');
    }

}
