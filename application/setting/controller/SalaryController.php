<?php

namespace app\setting\controller;

use think\App;
use app\common\model\Personnel;
use app\common\model\Position;
use app\common\model\SalaryPlan;
use think\Controller;
use think\Db;
use think\Request;
use app\common\model\Salary as SalaryModel;

class SalaryController extends Controller
{
    protected $model;
    protected $param;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SalaryModel();
    }

    /**
     * 显示资源列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request)
    {
        $companyId = $request->param('company_id');
        $apply = $request->get('apply');
        $applyId = $request->get('apply_id');
        /**获取方案信息**/
        $planInfo = SalaryPlan::getPlanInfo(SalaryPlan::PLAN_SALARY, $companyId, $apply, $applyId);

        $result = $this->model->where('plan_id', $planInfo['id'])->order('level_id')->select()->toArray();

        return jsonResponse($result, lang('SELECT_SUCCESS'), 200);
    }

    /**
     * 保存底薪
     * @param Request $request
     * @return \think\response\Json
     * @throws \Exception
     */
    public function save(Request $request)
    {
        $companyId = $request->param('company_id');
        $apply = $request->param('apply');
        $applyId = $request->param('apply_id');
        $salaryList = $request->param('salary_list');


        Db::startTrans();
        try {
            $name = SalaryPlan::generatePlanName($apply, $applyId, lang('SALARY_PLAN'), 'is_salary');
            $planId = SalaryPlan::putPlanInfo($companyId, $apply, $applyId, $name, SalaryPlan::PLAN_SALARY);

            foreach ($salaryList as &$item) {
                $item['company_id'] = $companyId;
                $item['plan_id'] = $planId;
            }
            $this->model->saveAll($salaryList);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return jsonResponse($e->getMessage(), lang('UPDATE_FAIL'), 400);
        }

        return jsonResponse([], lang('UPDATE_SUCCESS'), 200);
    }

    /**删除底薪
     * @param $id
     * @return \think\response\Json
     * @throws \Exception
     */
    public function delete()
    {
        $input = $this->request;
        $companyId = $input->param('company_id');
        $apply = $input->param('apply');
        $applyId = $input->param('apply_id');

        $planInfo = SalaryPlan::getPlanInfo(SalaryPlan::PLAN_SALARY, $companyId, $apply, $applyId);
        !$planInfo && exception('NO_DATA');
        $planId = $planInfo['id'];

        Db::startTrans();
        try {
            $this->model
                ->where('plan_id', $planId)
                ->delete();
            SalaryPlan::destroy($planId);
            switch ($apply) {
                case SalaryPlan::APPLY_PERSONAL:
                    Personnel::where('id', $applyId)->setField('is_salary', 0);
                    break;
                case SalaryPlan::APPLY_POSITION:
                    Position::where('id', $applyId)->setField('is_salary', 0);
                    break;
                default:
                    break;
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return jsonResponse($e->getMessage(), lang('DELETE_FAIL'), 400);
        }
        return returnTrue(lang('DELETE_SUCCESS'), []);
    }

}
