<?php

namespace app\setting\controller;

use app\common\model\Personnel;
use app\common\model\Position;
use app\common\model\SalaryMeritsLevel;
use app\common\model\SalaryPlan;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use \app\common\model\SalaryMerits as SalaryMeritsModel;
use \Exception;
use \think\exception\DbException;

class SalaryMeritsController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, SalaryMeritsModel $model)
    {
        parent::__construct($app);

        self::$model = $model;
    }

    /**
     * 显示资源列表
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $input = $this->request;
        $companyId = $input->param('company_id');
        $apply = $input->get('apply');
        $applyId = $input->get('apply_id');

        $where = [];

        /**获取方案信息**/
        $planInfo = SalaryPlan::getPlanInfo(SalaryPlan::PLAN_MERITS, $companyId, $apply, $applyId);
        $where['plan_id'] = $planInfo['id'];
        $meritsData = self::$model
            ->where($where)
            ->select()
            ->toArray();
        $data['merits_data'] = $meritsData;

        if ($apply == SalaryPlan::APPLY_POSITION) {
            $meritsLevelData = SalaryMeritsLevel::where($where)->selectOrFail()->toArray();
            $data['merits_level_data'] = $meritsLevelData;
        }
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
        $companyId = $param['company_id'];
        $apply = $param['apply'];
        $applyId = $param['apply_id'];
        $type = $param['type'];
        $meritsList = $param['merits_list'];
        $meritsLevelList = $param['merits_level_list'];
        $name = $name = SalaryPlan::generatePlanName($apply, $applyId, lang('MERITS_PLAN'));

        Db::startTrans();
        try {
            $planId = SalaryPlan::putPlanInfo($companyId, $apply, $applyId, $name, SalaryPlan::PLAN_MERITS);

            foreach ($meritsList as &$item) {
                $item['company_id'] = $companyId;
                $item['plan_id'] = $planId;
                $item['type'] = $type;
            }
            /**删除对应职位或个人原有设置 再重新保存**/
            self::$model->where(['plan_id' => $planId, 'type' => $type])->delete();
            self::$model->saveAll($meritsList);

            /**apply为职位时才可以更新等级相关信息**/
            if ($apply == SalaryPlan::APPLY_POSITION && !empty($meritsLevelList)) {
                $tmp = [];
                $levelIdArr = [];
                $jsonIdArr = [];
                $SalaryMeritsLevel = new SalaryMeritsLevel();
                $haveList = $SalaryMeritsLevel->where(['plan_id' => $planId])->selectOrFail();
                foreach ($haveList as $v) {
                    $tmp[$v['level_id']] = $v['id'];    //临时存储 用于确定id的值
                    $levelIdArr[] = $v['level_id'];     //数据库中当前方案已有记录的的level_id集合
                }
                foreach ($meritsLevelList as &$val) {
                    if (in_array($val['level_id'], $levelIdArr)) {
                        $val['id'] = $tmp[$val['level_id']];
                    }
                    $val['company_id'] = $companyId;
                    $val['plan_id'] = $planId;
                    $jsonIdArr[] = $val['level_id'];
                }

                $SalaryMeritsLevel->saveAll($meritsLevelList);
                $delIdArr = array_diff($levelIdArr, $jsonIdArr);

                $SalaryMeritsLevel->whereIn('level_id', $delIdArr)->delete();
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return jsonResponse($e->getMessage(), lang('UPDATE_FAIL'), 400);
        }
        return jsonResponse([], lang('UPDATE_SUCCESS'), 200);
    }
}
