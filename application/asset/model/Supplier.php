<?php

namespace app\asset\model;

use think\Model;
use think\model\concern\SoftDelete;

class Supplier extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    // 设置json类型字段
    protected $json = ['contact'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    /**
     * 获取所有的采购数据
     *
     * @return \think\model\relation\HasMany
     */
    public function supplementPurchase()
    {
        return $this->hasMany('SupplementPurchase')
            ->where('current_status', 2);
    }

}
