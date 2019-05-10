<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Orders extends Model
{
    use \auto\Check;
    use SoftDelete;
    protected $autoWriteTimestamp = 'timestamp';
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';

    public function generateNumber(){
        $str = "DD";
        $dateStr = date('YmdHis');
        $randStr = rand(1000,9999);
        return $str.$dateStr.$randStr;
    }
}
