<?php

namespace app\project\controller;

use app\common\model\Accessory;
use app\common\model\Program;
use app\common\model\Task;
use think\App;
use think\Controller;
use think\Request;
use \app\common\model\TaskSubmit;
use \Exception;
use \think\exception\DbException;

class TaskSubmitController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';
    const TYPE_TASK_SUBMIT = 5;

    public function __construct(App $app, TaskSubmit $model)
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
        $task_id = $param['task_id'] ?? 0;

        $programList = $param['program_list'] ?? [];
        $accessoryList = $param['accessory_list'] ?? [];

        /*从task中获取负责人ID*/
        $task = Task::field('personnel_id')->get($task_id);

        $personnel_id = !$task ? exception('NO_MANAGER') : $task->personnel_id;

        $param['personnel_id'] = $personnel_id;

        /*从task_submit中查找task_id等于param中的task_id，并且status等于0的记录*/
        $find_task_submit = TaskSubmit::field('id')->where(['task_id' => $task_id])->find();

        $find_task_submit && exception('TASK_SUBMIT_NO_CHECK');

        $result = self::$model
            ->check($param, 'add')
            ->save($param);

        $accessory = new Accessory();

        $accessory->saveData($accessoryList, self::TYPE_TASK_SUBMIT, self::$model->id, $param['company_id']);

        if ($result) {
            return returnTrue(lang('SUBMIT_CHECK_SUCCESS'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('SUBMIT_CHECK_FAIL'), $param, self::PARAM_FLAG);
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
