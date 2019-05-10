<?php

namespace app\business\controller;

use app\common\model\Accessory;
use app\common\model\OrderPay;
use app\common\model\Program;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use \app\common\model\Orders;
use \Exception;
use \think\exception\DbException;

class OrdersController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, Orders $model)
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
     * 按条件查询全部列表
     * @param Request $request
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lists(Request $request){
        $input = $request->param();

        $where = [];
        $where['company_id'] = $input['company_id'];

        isset($input['status']) && !empty($input['status']) && $where['status'] = $input['status'];

        $data = self::$model
            ->where($where)
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
            /**生成订单号**/
            $orderNumber = self::$model->generateNumber();
            $param['number'] = $orderNumber;
        }

        $programList = isset($param['program_list']) ? $param['program_list'] : [];
        $payList = isset($param['pay_list']) ? $param['pay_list'] : [];
        $accessoryList = isset($param['accessory_list']) ? $param['accessory_list'] : [];

        Db::startTrans();
        try {
            self::$model
                ->check($param, 'save')
                ->save($param, $where);
            $orderId = self::$model->id;

            $program = new Program();
            $program->saveData($programList, Program::TYPE_ORDER, $orderId, $param['company_id']);
            $orderPay = new OrderPay();
            $orderPay->saveData($payList, $orderId, $param['company_id']);
            $accessory = new Accessory();
            $accessory->saveData($accessoryList, Accessory::TYPE_ORDER, $orderId, $param['company_id']);

            Db::commit();
            return returnTrue(lang('SAVE_SUCCESS'), $orderId, self::PARAM_FLAG);
        } catch (\Exception $e) {
            Db::rollback();
            return returnFalse(lang('SAVE_FAIL'), $e->getMessage(), self::PARAM_FLAG);
        }
    }

    /**
     * 显示指定的资源
     * @param $id
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read($id)
    {
        $input = $this->request;
        $fields = $input->param('param.fields', '*');
        $result = self::$model
            ->alias('ord')
            ->leftJoin('project_type pt', 'ord.project_type_id=pt.id')
            ->leftJoin('customer cus', 'ord.customer_id=cus.id')
            ->field([
                'ord.*',
                'cus.name as customer_name',
                'pt.name as project_type_name',
            ])
            ->get($id)
            ->toArray();
        if ($result) {
            $orderId = $result['id'];
            $result['program_list'] = Program::getView($orderId,Program::TYPE_ORDER);
            $result['pay_list'] = OrderPay::where(['order_id' => $orderId])->select()->toArray();
            $result['accessory_list'] = Accessory::getView($orderId,Accessory::TYPE_ORDER);
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
