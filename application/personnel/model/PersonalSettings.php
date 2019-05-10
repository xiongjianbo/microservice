<?php
/**
 * Created by PhpStorm.
 * User: 王楚
 * Date: 2019/4/16
 * Time: 15:41
 */

namespace app\personnel\model;

use think\Model;

class PersonalSettings extends Model
{
    public function personnel()
    {
        return $this->belongsTo('personnel');
    }
}