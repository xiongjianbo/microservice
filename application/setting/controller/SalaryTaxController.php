<?php

namespace app\setting\controller;

use think\App;
use think\Controller;
use think\Request;
use \app\common\model\SalaryTax;
use \Exception;
use \think\exception\DbException;

class SalaryTaxController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, SalaryTax $model)
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

        $where = [];
        $where['type'] = SalaryTax::TYPE_2019;
        $where['company_id'] = $companyId;

        $data = self::$model
            ->where($where)
            ->select()
            ->toArray();
        return returnTrue(lang('SELECT_SUCCESS'), $data);
    }

    /**
     * 保存新建的资源
     * @param Request $request
     * @return \think\response\Json
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\PDOException
     */
    public function save(Request $request)
    {
        $addData = [];
        $updateData = [];
        $param = $request->param();
        $companyId = $param['company_id'];
        $type = $param['type'];
        $taxBegin = $param['tax_begin'];
        $taxList = $param['list'];

        foreach ($taxList as $item) {
            $item['company_id'] = $companyId;
            $item['type'] = $type;
            $item['tax_begin'] = $taxBegin;
            $isId = isset($item['id']) ? 1 : 0;

            /**根据isID判断 需要更新或添加的数据**/
            if ($isId) {
                $updateData[] = $item;
            } else {
                $addData[] = $item;
            }
        }
        /**得到需要删除的数据**/
        $dbList = self::$model->where(['company_id' => $companyId, 'type' => $type])->select()->toArray();
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
