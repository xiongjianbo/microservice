<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class FileRouter extends Model
{
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

}
