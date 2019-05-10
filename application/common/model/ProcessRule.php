<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class ProcessRule extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';

    // 设置json类型字段
    protected $json = ['target'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'company_id'  =>  'integer',
        'p_id'       =>  'integer',
        'type'  =>  'integer',
        'level'      =>  'integer',
    ];
}
