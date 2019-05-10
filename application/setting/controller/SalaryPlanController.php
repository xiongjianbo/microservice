<?php

namespace app\setting\controller;

use app\common\model\SalaryMeritsLevel;
use think\App;
use think\Controller;
use think\Request;
use \app\common\model\SalaryPlan as SalaryPlanModel;
use \app\common\model\Salary;
use \app\common\model\SalaryMerits;
use \app\common\model\SalaryDeduction;
use \Exception;
use \think\exception\DbException;

class SalaryPlanController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, SalaryPlanModel $model)
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
        $input = $this->request;
        $companyId = $input->param('company_id');
        $type = $input->get('type');

        $where = [];
        $where['company_id'] = $companyId;
        $type && $where['type'] = $type;

        $listRows = $input->get('per_page', config('page.listRows'));
        $page = $input->get('page', 1);

        $data = self::$model
            ->where($where)
            ->paginate($listRows, false, [
                'page' => $page
            ])
            ->toArray();
        return returnTrue(lang('SELECT_SUCCESS'), $data);
    }

    /**
     * 保存新建的资源
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function save(Request $request)
    {
        $param = $request->param();
        $result = self::$model
            ->check($param, 'add')
            ->save($param);

        if ($result) {
            return returnTrue(lang('ADD_SUCCESS'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('ADD_FAIL'), $param, self::PARAM_FLAG);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param $id
     * @return array
     * @throws DbException
     */
    public function read($id)
    {
        $input = $this->request;
        $fields = $input->param('param.fields', '*');
        $result = self::$model
            ->field($fields)
            ->get($id)
            ->toArray();

        if ($result) {
            return returnTrue(lang('SELECT_SUCCESS'), $result);
        } else {
            return returnFalse(lang('NO_DATA'), $result);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $input = $this->request;
        $param = $input->param();

        $result = self::$model
            ->isUpdate(true)
            ->save($param);

        if ($result) {
            return returnTrue(lang('UPDATE_SUCCESS'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('UPDATE_FAIL'), $param, self::PARAM_FLAG);
        }
    }

    /**
     * 删除指定资源
     *
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $result = self::$model->destroy($id);
        if ($result) {
            return returnTrue(lang('DELETE_SUCCESS'), $id);
        } else {
            return returnFalse(lang('DELETE_FAIL'));
        }
    }

    public function getInfo($type, $apply, $applyId)
    {
        $list = [];
        $where['type'] = $type;
        $where['apply_id'] = $applyId;
        $where['apply'] = $apply;
        $planId = self::$model->getAutoPlanId($apply, $applyId);
        switch ($type) {
            case SalaryPlanModel::PLAN_SALARY:
                $list = Salary::where('plan_id', $planId)->order('level_id')->selectOrFail()->toArray();
                break;
            case SalaryPlanModel::PLAN_MERITS:
                $list['merits_data'] = SalaryMerits::where('plan_id', $planId)->selectOrFail()->toArray();
                if ($apply == SalaryPlanModel::APPLY_POSITION) {
                    $list['merits_level_data'] = SalaryMeritsLevel::where('plan_id',
                        $planId)->selectOrFail()->toArray();
                }
                break;
            case SalaryPlanModel::PLAN_DEDUCTION:
                $list = SalaryDeduction::where('plan_id', $planId)->selectOrFail()->toArray();
                break;
            default:
                break;
        }
        return $list;
    }

}
