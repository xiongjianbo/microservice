<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class CustomerInfo extends Model
{
    use \auto\Check;
    use SoftDelete;
    protected $autoWriteTimestamp = 'timestamp';
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';

}
