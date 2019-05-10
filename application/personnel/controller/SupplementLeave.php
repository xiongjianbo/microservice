<?php

namespace app\personnel\controller;

use app\common\model\ProcessStep;
use app\common\service\Process;
use app\personnel\model\AttendanceRecord;
use think\Db;
use think\Request;
use app\personnel\model\SupplementLeave as Leave;
use app\common\model\Personnel;

class SupplementLeave
{

    private $param;

    private $leave;

    private $process;

    public function __construct(Request $request, Process $process, Leave $leave)
    {
        $this->param = $request->param();
        $this->process = $process;
        $this->leave = $leave;
    }

    /**
     * 获取请假以及审批的详细信息
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception\DbException
     */
    public function show($id)
    {
        $data = $this->leave
            ->field("id,personnel_id,type,start_time,end_time,reason,attachment_uri,current_status,create_time,update_time")
            ->with(['personnelInfo' => function ($query) {
                $query->field("id,name,department_id")->with(['department' => function ($query) {
                    $query->field("id,name");
                }]);
            }, 'stepInfo' => function ($query) {
                $query->order('id', 'asc')
                    ->where('supplement_type', 'app\personnel\model\SupplementLeave')
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
     * 请假审批
     *
     * @param $id ProcessStep 表中的主键ID
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
                $leaveModel = $result['model'];
                $number = $leaveModel->personnelInfo->number;
                $dates = array_column($leaveModel->time_len, 'day');
                $oldData = Db::table('attendance_record')
                    ->where('number', $number)
                    ->whereIn('date', $dates)
                    ->column('*', 'date');
                $oldDataDates = array_column($oldData, 'date');

                $leave_type = $leaveModel->type;
                $saveDataArr = [];
                foreach ($leaveModel->time_len as $value) {
                    $saveData = [];
                    $saveData['leave'] = $value['hour'] / 8;
                    $saveData['date'] = $value['day'];
                    $saveData['number'] = $number;
                    $saveData['leave_type'] = $leave_type;
                    if (in_array($value['day'], $oldDataDates)) {
                        $saveData['id'] = $oldData[$value['day']]['id'];
                        $saveData['late'] = null;
                        $saveData['left_early'] = null;

                        $saveData['work'] = $oldData[$value['day']]['work'] + $saveData['leave'] > 1
                            ? 1 - $saveData['leave'] : $oldData[$value['day']]['work'];
                    }
                    $saveDataArr [] = $saveData;
                }
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
        $where[] = ['supplement_type', '=', 'app\personnel\model\SupplementLeave'];
        $whereString = "JSON_CONTAINS(verify_target,'" . $this->param['userInfo']->id . "','$')";

        $data = $stepModel->where($where)
            ->field("id,supplement_id,verify_status,verify_personnel_id")
            ->order('create_time', 'desc')
            ->where($whereString)
            ->with(['leaveInfo' => function ($query) {
                $query->field("id,personnel_id,start_time,end_time,create_time,type")
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
     * 获取请假列表
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
                return jsonResponse([], '不能查看指定员工的请假信息,权限不足!', 405);
            }
            $classArr = $this->param['personnel_id'];
        }

        $map[] = ['personnel_id', 'in', $classArr];

        $field = 'id,personnel_id,type,start_time,end_time,reason,attachment_uri,create_time,update_time,current_status,create_time';

        $data = $this->leave
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
     * 提交请假申请
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
        $getNextStep = $this->process->findNextStep($this->param['userInfo'], 1);

        if ($getNextStep === false) {
            return jsonResponse([], '系统没有对应的规则,不能提交该申请!', 404);
        }

        if ($getNextStep === null) {
            // 没有下一步
            return jsonResponse([], '系统没有对应审批者,请联系管理员新增', 405);
        }

        $this->leave->startTrans();

        $result = $this->leave
            ->allowField('personnel_id,type,start_time,end_time,reason,attachment_uri,time_len')
            ->save($this->param);
        $stepResult = $this->process
            ->insertStep($this->leave->id, 'app\personnel\model\SupplementLeave',
                $getNextStep['nextRuleId'], $getNextStep['nextPersonnel']);

        if ($result && $stepResult) {
            $this->leave->commit();
            return jsonResponse();
        }

        $this->leave->rollback();
        return jsonResponse([], '数据库写入失败!', 301);
    }
}
