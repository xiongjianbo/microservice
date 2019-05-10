<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class SalaryTax extends Model
{
    use \auto\Check;

    const TYPE_2019 = 1;    //超额累进税制-2019版
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

}
