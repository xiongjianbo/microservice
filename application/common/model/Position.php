<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Position extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    /**
     * 员工
     *
     * @return $this|\think\model\relation\hasMany
     */
    public function personnel()
    {
        return $this->hasMany('Personnel');
    }

    /**
     * 部门
     *
     * @return $this|\think\model\relation\belongsTo
     */
    public function department()
    {
        return $this->belongsTo('Department','department_id');
    }
}
