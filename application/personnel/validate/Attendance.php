<?php

namespace app\personnel\validate;

use think\Validate;

class Attendance extends Validate
{
    protected $rule = [
        'year' => 'require|dateFormat:Y',
        'month' => 'require|integer|monthValidate',
        'file' => 'file|fileExt:xlsx,xls',
        'number' => 'integer|max:8',
        'date' => 'date',
        'id' => 'integer|max:11',
        'work' => 'integer|between:0,8',
        'leave' => 'integer|between:0,8',
        'leave_type' => 'integer|between:0,3',
        'late' => 'dateFormat:H:i:s',
        'left_early' => 'dateFormat:H:i:s',
        'eight' => 'integer|between:0,1',
        'ten' => 'integer|between:0,1',
        'weekend' => 'weekendTime',
    ];

    protected $scene = [
        'index' => ['year', 'month'],
        'import' => ['file'],
        'show' => ['file'],
        'showinfo' => ['number', 'date'],
        'update' => ['id', 'work', 'leave', 'leave_type', 'late', 'left_early', 'eight', 'ten', 'weekend'],
    ];

    /**
     * 月份格式验证
     *
     * @param $month
     * @return bool
     */
    protected function monthValidate($month)
    {
        if (0 < $month && $month < 13) {
            return true;
        }
        return false;
    }

    /**
     * 时长格式验证
     *
     * @param $month
     * @return bool
     */
    protected function weekendTime($time)
    {
        if ($time == 0.5 || $time == 1) {
            return true;
        }
        return false;
    }
}
