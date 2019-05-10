<?php
namespace app\personnel\model;

use think\Model;

class AttendanceRecord extends Model
{
    // 设置当前模型对应的完整数据表名称
//    protected $table = 'attendance_record';
//    protected $pk = 'id';
    /**
     * @return \think\model\relation\BelongsTo
     */
    public function personnel()
    {
        return $this->belongsTo('app\common\model\Personnel','number','number');
    }
}
