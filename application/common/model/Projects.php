<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Projects extends Model
{
    use \auto\Check;
    use SoftDelete;
    protected $autoWriteTimestamp = 'timestamp';
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';

    /**
     * 自动生成项目编号
     * @return string
     */
    public function generateNumber()
    {
        $str = "XM";
        $dateStr = date('YmdHis');
        $randStr = rand(1000, 9999);
        return $str . $dateStr . $randStr;
    }
}
