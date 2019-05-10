<?php

namespace app\common\service;

use app\common\model\Department;
use app\common\model\ProcessStep;
use app\common\model\ProcessRule;

class Process
{
    private $processStep;

    private $processRule;

    private $department;

    /**
     * Process constructor.
     * @param ProcessStep $processStep
     * @param ProcessRule $processRule
     * @param Department $department
     */
    public function __construct(ProcessStep $processStep, ProcessRule $processRule, Department $department)
    {
        $this->processStep = $processStep;
        $this->processRule = $processRule;
        $this->department = $department;
    }

    /**
     * 进入下一步流程
     *
     * @param $id  int  当前所在的process_step表的ID
     * @param $status -1 不通过 其他值表示通过
     * @param $userInfo
     * @param $content
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function gotoNextStep($id, $status, $userInfo, $content)
    {
        $has = $this->processStep->get($id);
        if (is_null($has)) {
            return ['message' => '对应的记录不存在或者已删除!', 'code' => 404];
        }

        if ($has['verify_status'] != 0) {
            return ['message' => '该记录已审批!', 'code' => 301];
        }

        if (!in_array($userInfo->id, $has['verify_target'])) {
            return ['message' => '没有对应的操作权限!', 'code' => 403];
        }

        $stepArr = [
            'verify_personnel_id' => $userInfo->id,
            'verify_time' => date('Y-m-d H:i:s'),
            'verify_content' => $content,
        ];

        if ($status == -1) {
            //审核不通过
            // 1.修改step表
            $stepArr['verify_status'] = -1;
            $this->processStep->isUpdate(true)->save($stepArr, ['id' => $id]);
            // 2.修改对应关联的表
            $model = new $has['supplement_type'];
            $model->isUpdate(true)->save(['current_status' => -1], ['id' => $has['supplement_id']]);
            return ['message' => '审核不通过,流程结束', 'code' => 201];
        } else {
            // 1.修改step表
            $stepArr['verify_status'] = 1;
            $this->processStep->isUpdate(true)->save($stepArr, ['id' => $id]);
            // 查找下一步
            $ruleInfo = $this->processRule->get($has['process_rule_id']);

            $nextInfo = $this->findNextStep($userInfo, $ruleInfo['type'], $has['process_rule_id']);
            if (!$nextInfo) {
                // 没有下一步,那么通过
                $model = $has['supplement_type']::get($has['supplement_id']);
                $model->current_status = 1;
                $model->save();
                return ['message' => '审核通过,流程结束', 'code' => 202, 'model' => $model];
            } else {
                // 进入下一步
                $this->insertStep($has['supplement_id'], $has['supplement_type'], $nextInfo['nextRuleId'], $nextInfo['nextPersonnel']);
                return ['message' => '审核通过,进入下一步', 'code' => 204];
            }
        }
    }

    /**
     * 查找部门信息对应的规则
     *
     * @param $departmentId
     * @param $companyId
     * @param $type
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function findDepartmentRule($departmentId, $companyId, $type)
    {
        $map[] = ['company_id', '=', $companyId];
        $map[] = ['type', '=', $type];
        $map[] = ['level', '=', 3];
        $map[] = ['p_id', '=', 0];
        $whereString = "JSON_CONTAINS(target,'" . $departmentId . "','$')";
        $has = $this->processRule->where($map)->where($whereString)->find();

        if ($has) {
            return $has;
        }

        $departmentInfo = $this->department->get($departmentId);
        if (is_null($departmentInfo)) {
            return null;
        }

        $departmentFatherInfo = $departmentInfo['path'];
        $departmentArr = explode(',', $departmentFatherInfo);
        if (empty($departmentArr)) {
            return null;
        }

        $newArr = array_reverse($departmentArr);

        foreach ($newArr as $item) {

            $String = "JSON_CONTAINS(target,'" . $item . "','$')";
            $hasNext = $this->processRule->where($map)->where($String)->find();

            if ($hasNext) {
                return $hasNext;
            }
        }
        return null;
    }

    /**
     * 查找下一个步骤
     *
     * @param $userInfo
     * @param $type
     * @param int $nowRuleId 如果是发起者第一步,那么为0
     * @return array|null|false  false表示没有制定该流程,不能发起操作  null表示流程结束,状态值更改   ['nextRuleId'=>2,'nextPersonnel'=>[1,2]]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findNextStep($userInfo, $type, $nowRuleId = 0)
    {
        $Map[] = ['company_id', '=', $userInfo->company_id];
        $Map[] = ['type', '=', $type];

        if ($nowRuleId == 0) {
            // 流程第一步
            $personnelId = $userInfo->id;
            $departmentId = $userInfo->department_id;
            $positionId = $userInfo->position_id;

            $wherePersonnelString = "JSON_CONTAINS(target,'" . $personnelId . "','$')";
            $wherePersonnelLevel[] = ['level', '=', 1];

            $whereMap[] = ['p_id', '=', 0];
            $hasPersonnelRule = $this->processRule
                ->where($whereMap)
                ->where($Map)
                ->where($wherePersonnelString)
                ->where($wherePersonnelLevel)
                ->find();
            if ($hasPersonnelRule) {
                // 存在这个人对应的规则
                $nextRuleInfo = $this->processRule
                    ->where('p_id', $hasPersonnelRule['id'])
                    ->find();
            } else {
                // 查找对应的职位有没有
                $wherePositionString = "JSON_CONTAINS(target,'" . $positionId . "','$')";
                $wherePositionLevel[] = ['level', '=', 2];

                $hasPositionRule = $this->processRule
                    ->where($Map)
                    ->where($whereMap)
                    ->where($wherePositionString)
                    ->where($wherePositionLevel)
                    ->find();
                if ($hasPositionRule) {
                    $nextRuleInfo = $this->processRule
                        ->where('p_id', $hasPositionRule['id'])
                        ->find();
                } else {
                    //查找本部门有没有
                    $hasDepartment = $this->findDepartmentRule($departmentId, $userInfo->company_id, $type);
                    if ($hasDepartment) {
                        $nextRuleInfo = $this->processRule
                            ->where('p_id', $hasDepartment['id'])
                            ->find();
                    } else {
                        // 使用公司试用的所有规则
                        $whereCompanyLevel[] = ['level', '=', 4];
                        $hasCompany = $this->processRule
                            ->where($Map)
                            ->where($whereMap)
                            ->where($whereCompanyLevel)
                            ->find();
                        if ($hasCompany) {
                            $nextRuleInfo = $this->processRule
                                ->where('p_id', $hasCompany['id'])
                                ->find();
                        } else {
                            return false;
                        }
                    }
                }
            }
            if (is_null($nextRuleInfo)) {
                return null;
            }
            return [
                'nextRuleId' => $nextRuleInfo['id'],
                'nextPersonnel' => $nextRuleInfo['target'],
            ];
        }

        $nextWhere[] = ['p_id', '=', $nowRuleId];

        $hasNext = $this->processRule->where($nextWhere)->find();

        if (is_null($hasNext)) {
            return null;
        }

        return [
            'nextRuleId' => $hasNext['id'],
            'nextPersonnel' => $hasNext['target'],
        ];
    }

    /**
     * 往数据库中插入一条审批流程
     *
     * @param $supplementId
     * @param $supplementType
     * @param $processRuleId
     * @param $verifyTarget
     * @return bool
     */
    public function insertStep($supplementId, $supplementType, $processRuleId, $verifyTarget)
    {

        $arr = [
            'supplement_id' => $supplementId,
            'supplement_type' => $supplementType,
            'process_rule_id' => $processRuleId,
            'verify_target' => $verifyTarget,
        ];
        return $this->processStep->allowField('supplement_id,supplement_type,process_rule_id,verify_target')
            ->isUpdate(false)->save($arr);
    }
}
