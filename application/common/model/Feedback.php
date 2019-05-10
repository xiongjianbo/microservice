<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Feedback extends Model
{
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

    //反馈详情
    public function feedbackInfo()
    {
        return $this->hasOne('FeedbackInfo');
    }

    //项目
    public function project()
    {
        return $this->belongsTo('Project','feedback_id');
    }

    //任务
    public function task()
    {
        return $this->belongsTo('Task','feedback_id');
    }

    //客户
    public function customer ()
    {
        return $this->belongsTo('Customer');
    }

}
