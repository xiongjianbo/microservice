<?php

namespace app\business\controller;

use think\App;
use think\Controller;
use think\Request;
use think\Db;
use \app\common\model\Customer;
use \app\common\model\CustomerInfo;
use \Exception;
use \think\exception\DbException;

class CustomerController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, Customer $model)
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

        $listRows = $input->get('per_page', config('page.listRows'));
        $page = $input->get('page', 1);

        $data = self::$model
            ->field([
                'customer.*',
                'customer_info.id AS customer_info_id',
                "customer_info.is_default",
                "customer_info.contact_person",
                "customer_info.telephone",
                "customer_info.skype",
                "customer_info.email",
                "customer_info.other_contact_info",
            ])
            ->leftJoin('customer_info', 'customer_info.customer_id = customer.id AND customer_info.is_default=1')
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

        $customer_info = new CustomerInfo;

        Db::startTrans();
        try {
            self::$model
                ->check($param, 'add')
                ->save($param);
            $last_id = self::$model->id;
            $contactData = $param['contact'] ?? [];
            /*遍历并给数据添加customer_id*/
            $contactData = array_map(function ($row) use ($last_id) {
                $row['customer_id'] = $last_id;
                return $row;
            }, $contactData);
            $customer_info->checkAll($contactData, 'add')->insertAll($contactData);
            /*提交事务*/
            Db::commit();
        } catch (\Exception $e) {
            /*回滚事务*/
            Db::rollback();
            return returnFalse(lang('ADD_FAIL'), $param, self::PARAM_FLAG);
        }
        $param['last_id'] = $last_id;
        return returnTrue(lang('ADD_SUCCESS'), $param, self::PARAM_FLAG);
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
            ->where(['id' => $id])
            ->findOrEmpty();
        $result['contact'] = [];
        if (!$result->isEmpty()) {
            $result = $result->toArray();
            $contact = CustomerInfo::where(['customer_id' => $id])->select();
            $result['contact'] = $contact ? $contact->toArray() : [];
        }
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
        $input = $request;
        $param = $input->param();
        $customer_info = new CustomerInfo;

        Db::startTrans();
        try {
            self::$model
                ->check($param, 'edit')
                ->isUpdate(true)
                ->save($param);
            $contactData = $param['contact'] ?? [];
            /*遍历并给数据添加customer_id*/
            $contactData && $customer_info->checkAll($contactData, 'edit')->saveAll($contactData);
            /*提交事务*/
            Db::commit();
        } catch (\Exception $e) {
            /*回滚事务*/
            Db::rollback();
            return returnFalse(lang('UPDATE_FAIL'), $param, self::PARAM_FLAG);
        }
        return returnTrue(lang('UPDATE_SUCCESS'), $param, self::PARAM_FLAG);
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


    public function customerInfoTrashed($id)
    {
        $result = CustomerInfo::onlyTrashed()->where(['customer_id'=>$id])->select();
        if($result->isEmpty()){
            return returnFalse(lang('NO_DATA'), $result);
        }else{
            return returnTrue(lang('SELECT_SUCCESS'), $result);
        }
    }
}
