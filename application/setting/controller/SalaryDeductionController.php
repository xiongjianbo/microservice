<?php

namespace app\setting\controller;

use app\common\model\SalaryPlan;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use \app\common\model\SalaryDeduction;
use \Exception;
use \think\exception\DbException;

class SalaryDeductionController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, SalaryDeduction $model)
    {
        parent::__construct($app);

        self::$model = $model;
    }

    /**
     * 显示资源列表
     * @return array
     * @throws DbException
     */
    public function index()
    {
        $request = $this->request;
        $companyId = $request->param('company_id');
        $apply = $request->get('apply');
        $applyId = $request->get('apply_id');
        /**获取方案信息**/
        $planInfo = SalaryPlan::getPlanInfo(SalaryPlan::PLAN_DEDUCTION, $companyId, $apply, $applyId);

        $result = self::$model->where('plan_id', $planInfo['id'])->select()->toArray();

        return returnTrue(lang('SELECT_SUCCESS'), $result);
    }

    /**
     * 保存新建的资源
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function save(Request $request)
    {
        $data = $request->only([
            'insurance',
            'insurance_type',
            'late',
            'late_type',
            'early',
            'early_type',
            'thing_leave',
            'thing_leave_type',
            'sick_leave',
            'sick_leave_type'
        ]);
        $data['company_id'] = $companyId = $request->param('company_id');
        $apply = $request->param('apply');
        $applyId = $request->param('apply_id');
        $name = SalaryPlan::generatePlanName($apply, $applyId, lang('DEDUCTION_PLAN'));
        Db::startTrans();
        try {
            $planId = SalaryPlan::putPlanInfo($companyId, $apply, $applyId, $name, SalaryPlan::PLAN_DEDUCTION);
            $data['plan_id'] = $planId;
            if (self::$model->where(['plan_id' => $planId])->find()) {
                self::$model->where(['plan_id' => $planId])->update($data);
            } else {
                self::$model->where(['plan_id' => $planId])->insert($data);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return jsonResponse($e->getMessage(), lang('UPDATE_FAIL'), 400);
        }
        return jsonResponse([], lang('UPDATE_SUCCESS'), 200);
    }
}
