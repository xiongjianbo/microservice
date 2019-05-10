<?php

namespace app\setting\controller;

use think\App;
use app\common\model\Salary;
use think\Controller;
use think\Request;
use app\common\model\SalaryLevel as SalaryLevelModel;

class SalaryLevelController extends Controller
{

    protected $model;
    protected $param;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SalaryLevelModel();
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
        $positionId = $request->param('position_id');
        $result = $this->model->where('position_id', $positionId)->order('level')->select()->toArray();
        return jsonResponse($result, lang('SELECT_SUCCESS'), 200);
    }


    /**
     * 保存等级设置
     * 可以增删改
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */

    public function save(Request $request)
    {
        $companyId = $request->param('company_id');
        $positionId = $request->param('position_id');
        $levelList = $request->param('level_list');
        $addData = [];
        $updateData = [];

        foreach ($levelList as $item) {
            $item['position_id'] = $positionId;
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
        $dbList = $this->model->where('position_id', $positionId)->select()->toArray();
        $dbIds = array_column($dbList, 'id');
        $jsonIds = array_column($updateData, 'id');
        $delIds = array_diff($dbIds, $jsonIds);

        $this->model->startTrans();
        try {
            $this->model->saveAll($addData);
            $this->model->saveAll($updateData);
            $this->model->whereIn('id', $delIds)->delete();
            /**删除底薪中对应的等级记录**/
            Salary::whereIn('level_id', $delIds)->delete();

            $this->model->commit();
        } catch (\Exception $e) {
            $this->model->rollback();
            $msg = $e->getMessage();
            return jsonResponse($msg, lang('UPDATE_FAIL'), 400);
        }
        return jsonResponse([], lang('UPDATE_SUCCESS'), 200);
    }

    /**
     * 显示单条记录
     * @param $id
     * @return \think\response\Json
     * @throws \Exception
     */
    public function read($id)
    {
        $result = $this->model->find($id);
        !$result && exception('NO_DATA');
        return jsonResponse($result, lang('SELECT_SUCCESS'), 200);
    }


    /**
     * 更新单条记录
     * @param $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \Exception
     */
    public function update($id, Request $request)
    {
        $data = [
            'name' => $request->param('name'),
            'level' => $request->param('level'),
            'bonus' => $request->param('bonus'),
        ];
        $result = $this->model->check($data, 'edit')->save($data, ['id' => $id]);
        return jsonResponse($result, lang('SAVE_SUCCESS'), 200);
    }
}
