<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class ProcessStep extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 设置json类型字段
    protected $json = ['verify_target'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'supplement_id'  =>  'integer',
        'process_rule_id'       =>  'integer',
        'verify_status'  =>  'integer',
        'verify_personnel_id'      =>  'integer',
    ];

    /**
     * 关联到审核人员员工信息表
     *
     * @return \think\model\relation\BelongsTo
     */
    public function personnelInfo(){
        return $this->belongsTo('app\common\model\Personnel','verify_personnel_id');
    }

    /**
     * 关联到请假表
     * @return \think\model\relation\BelongsTo
     */
    public function leaveInfo(){
        return $this->belongsTo('app\personnel\model\SupplementLeave','supplement_id');
    }

    /**
     * 关联到补卡时申请表
     * @return \think\model\relation\BelongsTo
     */
    public function signInfo(){
        return $this->belongsTo('app\personnel\model\SupplementSign','supplement_id');
    }

    /**
     * 获取申请对应的多态模型。
     */
    public function supplement()
    {
        return $this->morphTo();
    }
}
