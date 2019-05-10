<?php

namespace app\setting\controller;

use think\App;
use think\Controller;
use think\Request;
use \app\common\model\SalaryInsurance;
use \Exception;
use \think\exception\DbException;

class SalaryInsuranceController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, SalaryInsurance $model)
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

        $where = [];
        $where['company_id'] = $input->param('company_id');

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
        $addData = [];
        $updateData = [];
        $param = $request->param();
        $companyId = $param['company_id'];
        $insuranceList = $param['list'];

        foreach ($insuranceList as $item) {
            $item['company_id'] = $companyId;
            $isId = isset($item['id']) ? 1 : 0;

            /**根据isID判断 需要更新或添加的数据**/
            if ($isId) {
                $updateData[] = $item;
            } else {
                $addData[] = $item;
            }
        }
        /**得到需要删除的数据**/
        $dbList = self::$model->where('company_id', $companyId)->select()->toArray();
        $dbIds = array_column($dbList, 'id');
        $jsonIds = array_column($updateData, 'id');
        $delIds = array_diff($dbIds, $jsonIds);

        self::$model->startTrans();
        try {
            self::$model->saveAll($addData);
            self::$model->saveAll($updateData);
            self::$model->whereIn('id', $delIds)->delete();

            self::$model->commit();
        } catch (\Exception $e) {
            self::$model->rollback();
            $msg = $e->getMessage();
            return jsonResponse($msg, lang('UPDATE_FAIL'), 400);
        }
        return jsonResponse([], lang('UPDATE_SUCCESS'), 200);
    }
}
