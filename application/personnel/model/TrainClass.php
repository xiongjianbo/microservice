<?php

namespace app\personnel\model;

use think\Model;
use think\model\concern\SoftDelete;

class TrainClass extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 培训对象和参与类型,一旦新增，不能修改
    protected $readonly = ['join_type', 'target'];

    // 设置json类型字段
    protected $json = ['target'];

    // 设置JSON字段的类型
    protected $jsonType = [
        'target->is_all'	=>	'bool',
        'target->personnel'	=>	'array',
    ];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'current_status'  =>  'integer',
        'join_type'       =>  'integer',
        'most_personnel'  =>  'integer',
        'company_id'      =>  'integer',
    ];

    /**
     *  关联报名表
     * @return \think\model\relation\HasMany
     */
    public function joinedPersonnel(){
        return $this->hasMany('TrainPersonnel','train_id','id');
    }

    /** 关联资源表
     * @return \think\model\relation\HasOne
     */
    public function classResource(){
        return $this->hasOne('TrainResource','train_id','id');
    }

    /**
     * 获取所有针对培训课程的评论。
     */
    public function comments()
    {
        return $this->morphMany('Comment', 'commentable');
    }
}
