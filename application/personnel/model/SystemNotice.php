<?php

namespace app\personnel\model;

use think\Model;
use think\model\concern\SoftDelete;

class SystemNotice extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'company_id'  =>  'integer',
        'personnel_id'       =>  'integer',
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
     * 关联到公告通知表
     *
     * @return \think\model\relation\HasMany
     */
    public function noticePersonnel(){
        return $this->hasMany('SystemNoticePersonnel','system_notice_id');
    }

    /**
     * 关联到公告通知表
     *
     * @return \think\model\relation\HasMany
     */
    public function noticePersonnelRead(){
        return $this->hasMany('SystemNoticePersonnel','system_notice_id');
    }
}
