<?php

namespace app\project\controller;

use app\common\model\Accessory;
use app\common\model\Program;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use \app\common\model\Projects;
use \Exception;
use \think\exception\DbException;

class ProjectsController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, Projects $model)
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
        $where['pro.company_id'] = $input->param('company_id');
        $listRows = $input->get('per_page', config('page.listRows'));
        $page = $input->get('page', 1);

        $data = self::$model
            ->alias('pro')
            ->leftJoin('personnel per', 'pro.personnel_id=per.id')
            ->leftJoin('orders ord', 'pro.order_id=ord.id')
            ->leftJoin('project_type pt', 'pro.project_type_id=pt.id')
            ->where($where)
            ->field("pro.id,pro.number,ord.number as order_number, pro.name, per.name as personnel_name, pt.name as project_type_name, pro.expect_date, pro.status")
            ->paginate($listRows, false, [
                'page' => $page
            ])
            ->toArray();
        return returnTrue(lang('SELECT_SUCCESS'), $data);
    }

    /**
     * 按条件查询全部列表
     * @param Request $request
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lists(Request $request)
    {
        $input = $request->param();

        $where = [];
        $where['pro.company_id'] = $input['company_id'];

        isset($input['status']) && !empty($input['status']) && $where['pro.status'] = $input['status'];
        isset($input['parent_id']) && !empty($input['parent_id']) && $where['pro.parent_id'] = $input['parent_id'];
        $data = self::$model
            ->alias('pro')
            ->leftJoin('personnel per','pro.personnel_id = per.id')
            ->leftJoin('project_type pt', 'pro.project_type_id=pt.id')
            ->where($where)
            ->field([
                'pro.id',
                'pro.number',
                'pro.name',
                'per.name as personnel_name',
                'pt.name as project_type_name',
                'pro.expect_date',
                'pro.status'
            ])
            ->select()
            ->toArray();
        return returnTrue(lang('SELECT_SUCCESS'), $data);
    }

    /**
     * 保存新建的资源
     * @param Request $request
     * @return array
     */
    public function save(Request $request)
    {
        $where = [];
        $param = $request->param();

        if (isset($param['id'])) {
            $where['id'] = $param['id'];
        } else {
            /**生成项目编号**/
            $projectNumber = self::$model->generateNumber();
            $param['number'] = $projectNumber;
        }
        $programList = isset($param['program_list']) ? $param['program_list'] : [];
        $accessoryList = isset($param['accessory_list']) ? $param['accessory_list'] : [];

        Db::startTrans();
        try {
            self::$model
                ->check($param, 'save')
                ->save($param, $where);
            $projectId = self::$model->id;

            $program = new Program();
            $program->saveData($programList, Program::TYPE_PROJECT, $projectId, $param['company_id']);
            $accessory = new Accessory();
            $accessory->saveData($accessoryList, Accessory::TYPE_PROJECT, $projectId, $param['company_id']);

            Db::commit();
            return returnTrue(lang('SAVE_SUCCESS'), $projectId);
        } catch (\Exception $e) {
            Db::rollback();
            return returnFalse(lang('SAVE_FAIL'), $e->getMessage(), self::PARAM_FLAG);
        }
    }

    /** 显示指定的资源
     * @param $id
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read($id)
    {
        $result = self::$model
            ->alias('pro')
            ->leftJoin('personnel per', 'pro.personnel_id=per.id')
            ->leftJoin('orders ord', 'pro.order_id=ord.id')
            ->leftJoin('project_type pt', 'pro.project_type_id=pt.id')
            ->leftJoin('customer cus', 'pro.customer_id=cus.id')
            ->field([
                'pro.parent_id',
                'pro.number',
                'pro.order_id',
                'ord.number as order_number',
                'pro.name',
                'pro.expect_money',
                'pro.currency_id',
                'pro.personnel_id',
                'per.name as personnel_name',
                'pro.customer_id',
                'cus.name as customer_name',
                'pro.project_type_id',
                'pt.name as project_type_name',
                'pro.expect_date',
                'pro.done_date',
                'pro.begin_date',
                'pro.status',
            ])
            ->find($id)
            ->toArray();

        if ($result) {
            $result['program_list'] = Program::getView($id,Program::TYPE_PROJECT);
            $result['accessory_list'] = Accessory::getView($id,Accessory::TYPE_PROJECT);

            return returnTrue(lang('SELECT_SUCCESS'), $result);
        } else {
            return returnFalse(lang('NO_DATA'), $result);
        }
    }


    /**
     * 删除指定资源
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
}
