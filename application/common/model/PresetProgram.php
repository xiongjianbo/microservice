<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class PresetProgram extends Model
{
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

    public static function getParentId($id,$type)
    {
        $info = PresetProgram::find($id);
        if ($info && $info['type'] == $type) {
            return $info['parent_id'];
        }
        return 0;
    }
}
