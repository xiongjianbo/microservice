<?php

namespace app\common\model;

use think\Model;

class SalaryPlan extends Model
{
    use \auto\Check;

    const PLAN_SALARY = 1; //底薪方案
    const PLAN_MERITS = 2; //奖金方案
    const PLAN_DEDUCTION = 3; //扣款方案

    const APPLY_PERSONAL = 1; //应用的个人
    const APPLY_POSITION = 2; //应用到职位
    const APPLY_DEPARTMENT = 3; //应用的部门
    const APPLY_COMPANY = 4;    //应用到全公司

    /**
     * 获取方案信息
     * @param $companyId
     * @param $apply
     * @param $applyId
     * @return array|bool|\PDOStatement|string|Model
     */
    public static function getPlanInfo($type, $companyId, $apply, $applyId)
    {
        $where = [];
        $where['type'] = $type;
        $where['company_id'] = $companyId;
        $where['apply'] = $apply;
        $where['apply_id'] = $applyId;
        try {
            return self::where($where)->findOrFail()->toArray();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *更新方案信息
     */
    public static function putPlanInfo($companyId, $apply, $applyId, $name, $type)
    {
        $planData = [
            'type' => $type,
            'company_id' => $companyId,
            'apply' => $apply,
            'apply_id' => $applyId,
            'name' => $name,
        ];

        $planInfo = self::getPlanInfo($type, $companyId, $apply, $applyId);
        $id = $planInfo ? $planInfo['id'] : self::insertGetId($planData);
        return $id;

    }

    /**
     * 生成方案名称
     */
    public static function generatePlanName($apply, $applyId, $name = '', $field = '')
    {
        switch ($apply) {
            case SalaryPlan::APPLY_PERSONAL:
                $personnelName = Personnel::where('id', $applyId)->value('name');
                $name = $personnelName . $name;

                if ($field != '') {
                    Personnel::where('id', $applyId)->setField($field, 1);
                }
                break;
            case SalaryPlan::APPLY_POSITION:
                $positionName = Position::where('id', $applyId)->value('title');
                $name = $positionName . $name;

                if ($field != '') {
                    Position::where('id', $applyId)->setField($field, 1);
                }
                break;
            case SalaryPlan::APPLY_DEPARTMENT:
                $departmentName = Department::where('id', $applyId)->value('title');
                $name = $departmentName . $name;

                if ($field != '') {
                    Department::where('id', $applyId)->setField($field, 1);
                }
                break;
            case SalaryPlan::APPLY_COMPANY:
                $companyName = Company::where('id', $applyId)->value('title');
                $name = $companyName . $name;

                if ($field != '') {
                    Company::where('id', $applyId)->setField($field, 1);
                }
                break;
            default:
                break;
        }
        return $name;
    }

    /**
     * 按就近选择原则自动获取plan_id
     */
    public function getAutoPlanId($apply, $applyId)
    {
        $where = [];
        $where['apply'] = $apply;
        $where['apply_id'] = $applyId;
        $planId = self::where($where)->value('id');
        if ($planId) {
            return $planId;
        } else {
            switch ($apply) {
                case self::APPLY_PERSONAL:
                    $personnelInfo = Personnel::findOrEmpty($applyId);
                    empty($personnelInfo) && exception('NO_DATA_PERSONNEL');
                    $apply = self::APPLY_POSITION;
                    $applyId = $personnelInfo['position_id'];
                    break;
                case self::APPLY_POSITION:
                    $positionInfo = Position::findOrEmpty($applyId);
                    empty($positionInfo) && exception('NO_DATA_POSITION');
                    $apply = self::APPLY_DEPARTMENT;
                    $applyId = $positionInfo['department_id'];
                    break;
                case self::APPLY_DEPARTMENT:
                    $departmentInfo = Department::findOrEmpty($applyId);
                    empty($departmentInfo) && exception('NO_DATA_DEPARTMENT');
                    if ($departmentInfo['p_id'] != 0) {
                        $apply = self::APPLY_DEPARTMENT;
                        $applyId = $departmentInfo['pid'];
                    } else {
                        $apply = self::APPLY_COMPANY;
                        $applyId = $departmentInfo['company_id'];
                    }
                    break;
                case self::APPLY_COMPANY:
                    exception('NO_DATA_COMPANY');
                    break;
                default:
                    exception('WRONG_TYPE');
                    break;
            }
            return self::getAutoPlanId($apply, $applyId);
        }
    }

}
