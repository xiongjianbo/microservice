<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class SalaryMerits extends Model
{
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

    const TYPE_DAY = 1; //绩效日*基数*等级加成

}
