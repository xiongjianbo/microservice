<?php

namespace app\personnel\model;

use think\Model;
use think\model\concern\SoftDelete;

class SupplementSign extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 一旦新增，不能修改
    protected $readonly = ['personnel_id', 'type','day','sign_time','reason'];

    // 设置json类型字段
    protected $json = ['attachment_uri'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'personnel_id'  =>  'integer',
        'type'       =>  'integer',
        'current_status'  =>  'integer',
    ];

    /**
     * 关联到员工信息表
     *
     * @return \think\model\relation\BelongsTo
     */
    public function personnelInfo(){
        return $this->belongsTo('app\common\model\Personnel','personnel_id');
    }


    /**
     * 关联到步骤表
     *
     * @return \think\model\relation\HasMany
     */
    public function stepInfo(){
        return $this->hasMany('app\common\model\ProcessStep','supplement_id');
    }

    /**
     * 获取所有所有的请假流程。
     */
    public function sign()
    {
        return $this->morphMany('app\common\model\ProcessStep', 'supplement');
    }
}
