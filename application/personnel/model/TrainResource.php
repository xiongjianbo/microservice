<?php

namespace app\personnel\model;

use think\Model;
use think\model\concern\SoftDelete;

class TrainResource extends Model
{

    use SoftDelete;

    protected $pk = 'train_id';

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 设置json类型字段
    protected $json = ['photo','video','file'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    /** 关联资源表
     * @return \think\model\relation\belongsTo
     */
    public function classResource(){
        return $this->belongsTo('TrainClass','train_id','id');
    }
}
