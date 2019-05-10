<?php

namespace app\project\controller;

use think\App;
use think\Controller;
use think\Request;
use \app\common\model\Feedback;
use \Exception;
use \think\exception\DbException;

class FeedBackController extends Controller
{
    protected static $model;

    protected static $feedback_type;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, Feedback $model, Request $request)
    {
        parent::__construct($app);
        $this->param = $request->param();
        self::$model = $model;

    }

    /**
     * 显示资源列表
     * @return array
     * @throws DbException
     */
    public function index()
    {
        $data = self::$model->select()->toArray();

        foreach ($data as $item) {
            self::$feedback_type[] = $item['feedback_type'];
        }
        if (self::$feedback_type = 0) {
            $list = self::$model
                ->alias('f')
                ->join('project','f.feedback_id = project.id')
                ->join('customer','f.customer_id = customer.id')
                ->select()->toArray();

        } else {
            $list = self::$model
                ->alias('f')
                ->join('task','f.feedback_id = task.id')
                ->join('customer','f.customer_id = customer.id')
                ->select()->toArray();

        }

        pr($list);
        prd(self::$feedback_type);
        exit();


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
}
