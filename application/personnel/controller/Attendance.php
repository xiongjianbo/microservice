<?php

namespace app\personnel\controller;

use app\personnel\model\AttendanceRecord;
use think\Request;

class Attendance
{
    /**
     * @var AttendanceRecord
     */
    protected $attendanceRecord;
    protected $param;

    /**
     * Attendance constructor.
     * @param AttendanceRecord $attendanceRecord
     * @param Request $request
     */
    public function __construct(AttendanceRecord $attendanceRecord, Request $request)
    {
        $this->attendanceRecord = $attendanceRecord;
        $this->param = $request->param();
    }

    /**
     * 获取列表
     *
     * @return \think\response\Json
     */
    public function index()
    {
        $start = $this->param['year'] . '-' . $this->param['month'] . '-1';
        $end = $this->param['year'] . '-' . ($this->param['month'] + 1) . '-1';
        $data = $this->attendanceRecord
            ->field('number,sum(work) as work,
            count(late) as late,
            count(left_early) as left_early,
            count(eight) as eight,
            count(ten) as ten,
            sum(weekend) as weekend')
            ->group('number')
            ->where('date', '< time', $end)
            ->where('date', '>= time', $start)
            ->with(['personnel' => function ($query) {
                $query->field('name,number,id');
            }])
            ->select();

        $result = [];
        foreach ($data as $item) {
            $result[$item['number']] = $item;
        }

        $leaveData = $this->attendanceRecord
            ->field('number,sum(`leave`) as leave_day,
            leave_type')
            ->group('number,leave_type')
            ->where('leave_type', '>', 0)
            ->where('date', '< time', $end)
            ->where('date', '>= time', $start)
            ->select();

        foreach ($leaveData as $value) {
            $result[$value['number']]['leave_' . $value['leave_type']] = $value['leave_day'];
        }
        $result = array_values($result);
        return jsonResponse($result);
    }

    /**
     * 获取个人考勤详情
     *
     * @param $number
     * @return \think\response\Json
     */
    public function show($number)
    {
        $start = $this->param['year'] . '-' . $this->param['month'] . '-1';
        $end = $this->param['year'] . '-' . ($this->param['month'] + 1) . '-1';

        $holidays = getHoliday($this->param['year'], $this->param['month']);

        $data = $this->attendanceRecord
            ->field('date,start,end,late,left_early,work,weekend')
            ->where('date', '< time', $end)
            ->where('date', '>= time', $start)
            ->where('number', $number)
            ->select();

//        $result = [];
//        foreach ($data as $value) {
//            if ($value['start']) {
//                $result [] = ['title' => '上班打卡：' . $value['start'], 'start' => $value['date']];
//            }
//            if ($value['end']) {
//                $result [] = ['title' => '下班打卡：' . $value['end'], 'start' => $value['date']];
//            }
//            if ($value['late']) {
//                $result [] = ['title' => '迟到时长：' . $value['late'], 'start' => $value['date']];
//            }
//            if ($value['left_early']) {
//                $result [] = ['title' => '早退时长：' . $value['left_early'], 'start' => $value['date']];
//            }
//            if ($value['work'] != 1 && !in_array($value['date'], $holidays)) {
//                $result [] = ['title' => '出勤天数：' . $value['work'], 'start' => $value['date']];
//            }
//            if ($value['weekend'] != 0) {
//                $result [] = ['title' => '周末加班天数：' . $value['weekend'], 'start' => $value['date']];
//            }
//        }

        return jsonResponse($data);
    }

    /**
     * 获取个人考勤详情
     *
     * @param $number
     * @return \think\response\Json
     */
    public function showInfo($number, $date)
    {
        $data = $this->attendanceRecord
            ->where('date', $date)
            ->where('number', $number)
            ->find();
        $data->work = $data->work * 8;
        $data->leave = $data->leave * 8;
        return jsonResponse($data);
    }

    /**
     * 修改
     *
     * @param $id
     * @return $this|\think\response\Json
     */
    public function update($id)
    {
        $attendanceRecord = $this->attendanceRecord->get($id);
        if (!$attendanceRecord) {
            return jsonResponse([], '数据不存在', 404);
        }

        if (!empty($this->param['work'])) {
            $this->param['work'] = $this->param['work'] / 8;
        }

        if (!empty($this->param['leave'])) {
            $this->param['leave'] = $this->param['leave'] / 8;
        }

        $result = $attendanceRecord
            ->allowField(['work', 'leave', 'leave_type', 'late', 'left_early', 'eight', 'ten', 'weekend'])
            ->save($this->param);
        if (false === $result) {
            return jsonResponse([], '修改失败', 301);
        }
        return jsonResponse();
    }

    /**
     * 导入数据
     *
     * @return \think\response\Json
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \think\Exception
     */
    public function import()
    {

        $excelData = \excel\Excel::uploadExcel(0, 16);
        array_shift($excelData);

        $date = $excelData[0][0][3];

//        $data = $this->attendanceRecord->where('date', $date)->find();
//        if ($data) {
//            return json(['msg' => '本月数据已录'])->code(403);
//        }

        $dateArr = explode('-', $date);

        $year = $dateArr[0];
        $month = $dateArr[1];

        // 节假日
        $holidays = getHoliday($year, $month);

        // 旧数据
        $start = $year . '-' . $month . '-1';
        $end = $year . '-' . ($month + 1) . '-1';
        $oldDataSqlArr = $this->attendanceRecord
            ->field('id,number,date,leave')
            ->where('date', '< time', $end)
            ->where('date', '>= time', $start)
            ->select();
        if ($oldDataSqlArr) {
            $oldDataArr = [];
            foreach ($oldDataSqlArr as $value) {
                $oldDataArr[$value['number'] . $value['date']] = $value;
            }
        }

        $realData = [];
        foreach ($excelData as $value) {
            $row = current($value);
            $data = [];
            $data['number'] = $row[2];
            $data['date'] = $row[3];
            $oldData = $oldDataArr[(int)$data['number'] . $data['date']] ?? null;
            if ($oldData) {
                $data['id'] = $oldData['id'];
                $data['start'] = $oldData['start'] ?? null;
                $data['end'] = $oldData['end'] ?? null;
            }

            if (!empty($row[5]) && empty($data['start'])) {
                $data['start'] = $row[5];
            }
            if (empty($data['end'])) {
                for ($i = 6; $i <= 16; $i++) {
                    if (!empty($row[$i])) {
                        $data['end'] = $row[$i];
                    } else {
                        break;
                    }
                }
            }

            // 节假日判断
            $notHoliday = !in_array($data['date'], $holidays);


            // 迟到时长
            if (!empty($data['start']) && $notHoliday) {
                $diff = timeDiff($data['start'], '9:35');
                if (!$diff['above']) {
                    $data['late'] = $diff['diff'];
                }
            }

            // 早退时长
            if (!empty($data['end']) && $notHoliday) {
                // 下班时间
                $closing_time = "18:00";
                if (timeDiff("9:05", $data['start'])['above']) {
                    $closing_time = "18:30";
                }
                $diff = timeDiff($closing_time, $data['end']);
                if (!$diff['above']) {
                    $data['left_early'] = $diff['diff'];
                }
                // 下班时间（小时）
                $arr = explode(':', $data['end']);
                $end_hour = $arr[0];
                // 八点后下班
                if ($end_hour >= 20) {
                    $data['eight'] = 1;
                }
                // 十点后下班
                if ($end_hour >= 22) {
                    $data['ten'] = 1;
                }
            }

            // 出勤天数
            if (!empty($data['start']) && !empty($data['end'])) {

                $startDiff = timeDiff($data['start'], '9:35');
                $endDiff = timeDiff('18:00', $data['end']);
                if ($startDiff['above'] && $endDiff['above']) {
                    // 正常上下班
                    $hour = 8;
                } elseif ($startDiff['above'] && !$endDiff['above']) {
                    // 提前下班
                    $hour = timeDiff('9:00', $data['end'])['hour'];
                } elseif (!$startDiff['above'] && $endDiff['above']) {
                    // 延迟上班
                    $hour = timeDiff($data['start'], '18:30')['hour'];
                } else {
                    // 二者均有
                    $hour = timeDiff($data['start'], $data['end'])['hour'];
                }

                if ($notHoliday) {

                    // 工作日出勤天数
                    if ($hour >= 8) {
                        $data['work'] = 1;
                    } else {
                        $data['work'] = $hour / 8;
                    }
                    // 请假数据
                    if (!empty($oldData['leave'])) {
                        $data['late'] = null;
                        $data['left_early'] = null;
                        $shouldWork = 1 - $oldData['leave'];
                        $data['work'] = $data['work'] > $shouldWork ? $shouldWork : $data['work'];
                    }

                } else {
                    // 周末加班时长
                    if ($hour >= 8) {
                        $data['weekend'] = 1;
                    } elseif ($hour >= 3) {
                        $data['weekend'] = 0.5;
                    }
                }
            } else {
                $data['work'] = 0;
            }
            $realData[] = $data;
        }
        $result = $this->attendanceRecord->saveAll($realData);
        if (!$result) {
            return jsonResponse([], '导入失败', 301);
        }
        return jsonResponse(['year' => $dateArr[0], 'month' => $dateArr[1]]);
    }

}
