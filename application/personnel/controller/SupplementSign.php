<?php

namespace app\personnel\controller;

use app\common\model\ProcessStep;
use app\common\service\Process;
use app\personnel\model\AttendanceRecord;
use think\Db;
use think\facade\Cache;
use think\Request;
use app\personnel\model\SupplementSign as Sign;
use app\common\model\Personnel;

class SupplementSign
{

    private $param;

    private $sign;

    private $process;

    public function __construct(Request $request, Process $process, Sign $sign)
    {
        $this->param = $request->param();
        $this->process = $process;
        $this->sign = $sign;
    }

    /**
     * 获取补卡以及审批的详细信息
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception\DbException
     */
    public function show($id)
    {
        $data = $this->sign
            ->field("id,personnel_id,type,day,sign_time,reason,attachment_uri,current_status,create_time,update_time")
            ->with(['personnelInfo' => function ($query) {
                $query->field("id,name,department_id")->with(['department' => function ($query) {
                    $query->field("id,name");
                }]);
            }, 'stepInfo' => function ($query) {
                $query->order('id', 'asc')
                    ->where('supplement_type', 'app\personnel\model\SupplementSign')
                    ->field("verify_status,verify_time,verify_personnel_id,supplement_id,verify_content")
                    ->with(['personnelInfo' => function ($query) {
                        $query->field("id,name,department_id")->with(['department' => function ($query) {
                            $query->field("id,name");
                        }]);
                    }]);
            }])
            ->get($id);
        return jsonResponse($data);
    }

    /**
     * 补卡审批
     *
     * @param $id int ProcessStep表中的主键ID
     * @return \think\response\Json
     */
    public function update($id, AttendanceRecord $attendanceRecord)
    {
        $userInfo = $this->param['userInfo'];
        Db::startTrans();
        try {
            $result = $this->process->gotoNextStep($id, $this->param['verify_status'], $userInfo, $this->param['verify_content']);

            if ($result['code'] == 202) {
                // 更新考勤数据
                $signModel = $result['model'];
                $number = $signModel->personnelInfo->number;
                $oldData = $attendanceRecord
                    ->where('number', $number)
                    ->where('date', $signModel->day)
                    ->find();
                $data = [
                    'number' => $number,
                    'date' => $signModel->day,
                ];
                if ($signModel->type == 1) {
                    $data['start'] = $signModel->sign_time;
                } else {
                    $data['end'] = $signModel->sign_time;
                }
                if ($oldData) {
                    $data['id'] = $oldData->id;
                    $data['start'] = $data['start'] ?? $oldData->start;
                    $data['end'] = $data['end'] ?? $oldData->end;

                    // 考勤数据
                    if (!empty($data['start']) && !empty($data['end'])) {
                        // 节假日
                        $dateArr = explode('-', $data['date']);
                        $holidays = getHoliday($dateArr[0], $dateArr[1]);
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
                }
                $saveDataArr = [$data];
                $attendanceRecord->saveAll($saveDataArr);
            }

            Db::commit();
            if ($result['code'] > 300) {
                return jsonResponse([], $result['message'], $result['code']);
            }
            return jsonResponse([], $result['message']);
        } catch (\Exception $e) {
            Db::rollback();
            return jsonResponse([], $e->getMessage(), 301);
        }
    }

    /**
     * 获取待审批列表
     *
     * @param ProcessStep $stepModel
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function wait(ProcessStep $stepModel)
    {
        $current_status = $this->param['current_status'];

        $where[] = ['verify_status', '=', $current_status];
        $where[] = ['supplement_type', '=', 'app\personnel\model\SupplementSign'];
        $whereString = "JSON_CONTAINS(verify_target,'" . $this->param['userInfo']->id . "','$')";

        $data = $stepModel->where($where)
            ->field("id,supplement_id,verify_status,verify_personnel_id")
            ->order('create_time', 'desc')
            ->where($whereString)
            ->with(['signInfo' => function ($query) {
                $query->field("id,personnel_id,day,sign_time,create_time,type")
                    ->with(['personnelInfo' => function ($query) {
                        $query->field("id,name,department_id")->with(['department' => function ($query) {
                            $query->field('id,name');
                        }]);
                    }]);
            }, 'personnelInfo' => function ($query) {
                $query->field("id,name,department_id")->with(['department' => function ($query) {
                    $query->field('id,name');
                }]);
            }])
            ->paginate($this->param['limit']);


        return jsonResponse($data);
    }

    /**
     * 获取补卡列表
     *
     * @param Personnel $personnel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Personnel $personnel)
    {
        $departmentId = $this->param['userInfo']->department_id;
        $personnelId = $this->param['userInfo']->id;

        // 查看范围:1-所有;2-部门;3-自己
        switch ($this->param['ruleAuth']) {
            case 1:
                $classArr = $personnel->where('company_id', '=', $this->param['company_id'])->column('id');
                break;
            case 2:
                $classArr = $personnel->getDepartmentPersonnel($departmentId);
                break;
            case 3:
                $classArr = [$personnelId];
                break;
        }

        // 需要筛选部门
        if (isset($this->param['department_id'])) {
            $departmentArr = $personnel->getDepartmentPersonnel($this->param['department_id']);
            $classArr = array_intersect($departmentArr, $classArr);
        }

        // 需要筛选员工
        if (isset($this->param['personnel_id'])) {
            if (!in_array($this->param['personnel_id'], $classArr)) {
                return jsonResponse([], '不能查看指定员工的补卡信息,权限不足!', 405);
            }
            $classArr = $this->param['personnel_id'];
        }

        $map[] = ['personnel_id', 'in', $classArr];

        $field = 'id,personnel_id,type,day,sign_time,reason,attachment_uri,create_time,update_time,current_status,create_time';

        $data = $this->sign
            ->field($field)
            ->with([
                'personnelInfo' => function ($query) {
                    $query->withField('id,name,department_id')->with(['department' => function ($query) {
                        $query->withField('id,name');
                    }]);
                }
            ])->order('create_time', 'desc')
            ->where($map)->paginate($this->param['limit']);

        return jsonResponse($data);
    }

    /**
     * 提交补卡申请
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function store()
    {
        $this->param['personnel_id'] = $this->param['userInfo']->id;

        $signData = $this->sign
            ->where('personnel_id', $this->param['personnel_id'])
            ->where('day', $this->param['day'])
            ->where('sign_time', $this->param['sign_time'])
            ->where('day', '>=', 0)
            ->find();
        if ($signData) {
            return jsonResponse([], '不能重复提交申请!', 404);
        }

        $getNextStep = $this->process->findNextStep($this->param['userInfo'], 2);

        if ($getNextStep === false) {
            return jsonResponse([], '系统没有对应的规则,不能提交该申请!', 404);
        }

        if ($getNextStep === null) {
            // 没有下一步
            return jsonResponse([], '系统没有对应审批者,请联系管理员新增', 405);
        }

        $this->sign->startTrans();

        $result = $this->sign
            ->allowField('personnel_id,type,day,sign_time,reason,attachment_uri')
            ->save($this->param);
        $stepResult = $this->process
            ->insertStep($this->sign->id, 'app\personnel\model\SupplementSign',
                $getNextStep['nextRuleId'], $getNextStep['nextPersonnel']);

        if ($result && $stepResult) {
            $this->sign->commit();
            return jsonResponse();
        }

        $this->sign->rollback();
        return jsonResponse([], '数据库写入失败!', 301);
    }
}
