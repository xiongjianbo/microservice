<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class FeedbackInfo extends Model
{
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';
    //反馈
    public function feedback()
    {
        return $this->belongsTo('Feedback');
    }

    //员工
    public function personnel()
    {
        return $this->belongsTo('Personnel');
    }
}
