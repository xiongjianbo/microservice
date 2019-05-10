<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Language extends Model
{
    protected $autoWriteTimestamp = 'timestamp';
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

}
