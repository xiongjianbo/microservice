<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Comment extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';


    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'personnel_id'       =>  'integer',
        'commentable_id'  =>  'integer',
    ];

    /**
     * 获取评论对应的多态模型。
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * 关联到员工信息表
     *
     * @return \think\model\relation\BelongsTo
     */
    public function personnelInfo(){
        return $this->belongsTo('app\common\model\Personnel','personnel_id');
    }
}
