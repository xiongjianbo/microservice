<?php

namespace app\personnel\model;

use think\Model;
use think\model\concern\SoftDelete;

class SystemNoticePersonnel extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'system_notice_id'  =>  'integer',
        'personnel_id'       =>  'integer',
        'is_read'       =>  'integer',
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
     * 关联到系统公告表
     *
     * @return \think\model\relation\BelongsTo
     */
    public function noticeInfo(){
        return $this->belongsTo('SystemNotice','system_notice_id');
    }
}
