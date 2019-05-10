<?php

namespace app\project\controller;

use think\App;
use think\Controller;
use think\Request;
use think\Db;
use \app\common\model\Task;
use \app\common\model\File;
use \Exception;
use \think\exception\DbException;
use app\common\model\Program;
use app\common\model\Accessory;

class TaskController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';
    const TYPE_ORDER = 1;
    const TYPE_PROJECT = 2;
    const TYPE_TASK = 3;

    public function __construct(App $app, Task $model)
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

        $where = ['task.p_id' => 0];

        $listRows = $input->get('per_page', config('page.listRows'));
        $page = $input->get('page', 1);
        $name = $input->get('name', '');
        $status = $input->get('status', '');

        /*可根据任务检索*/
        if ($status) {
            !is_numeric($status) && exception('INVALID_ARGUMENT');
            $where['task.status'] = $status;
        }

        $whereOr = [];
        /*任务名称*/
        if ($name) {
            $where['task.name'] = $name;
            $whereOr['personnel.name'] = $name;
        }

        $data = self::$model
            ->where($where)
            ->whereOr($whereOr)
            ->field([
                'task.*',
                'projects.name as projects_name',
                'company.name as company_name',
                'personnel.name as personnel_name',
                'skill_category.name as skill_category_name',
            ])
            ->leftJoin('projects', 'projects.id = task.project_id')
            ->leftJoin('company', 'company.id = task.company_id')
            ->leftJoin('personnel', 'personnel.id = task.personnel_id')
            ->leftJoin('skill_category', 'task.type = skill_category.id')
            ->order('task.id', 'desc')
            ->paginate($listRows, false, [
                'page' => $page
            ])
            ->toArray();

        /*获取子任务*/
        $subTask = self::$model->getSubTask($data);

        return returnTrue(lang('SELECT_SUCCESS'), $subTask);
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
        /*获取参数中的taskList*/
        $taskList = $this->getTaskList($param);

        $programList = $param['program_list'] ?? [];
        $accessoryList = $param['accessory_list'] ?? [];

        $program = new Program();
        $accessory = new Accessory();

        Db::startTrans();
        try {

            $id_arr = self::$model
                ->checkAll($taskList, 'add')
                ->saveAll($taskList);

            $idList = self::$model->getIdList($id_arr);

            foreach ($idList as $taskId) {
                $program->saveData($programList, self::TYPE_TASK, $taskId, $param['company_id']);
                $accessory->saveData($accessoryList, self::TYPE_TASK, $taskId, $param['company_id']);
            }

            /*提交事务*/
            Db::commit();
        } catch (\Exception $e) {
            /*回滚事务*/
            Db::rollback();
            return returnFalse(lang('ADD_FAIL'), $param, self::PARAM_FLAG);
        }
        return returnTrue(lang('ADD_SUCCESS'), $param, self::PARAM_FLAG);
    }

    /**
     * 从参数中获取task_list
     * @param array $data
     * @return array
     */
    protected function getTaskList($data = [])
    {
        return array_map(function ($row) use ($data) {
            $hasPersonnel = isset($row['personnel_id']) && !empty($row['personnel_id']);
            $way1 = ($row['way'] ?? 1) == 1;
            /*有负责人且发布到的不是任务大厅*/
            $row['status'] = $hasPersonnel && !$way1 ? 3 : 1;
            $row['task_number'] = self::$model::getTaskNumber($data['company_id'] ?? 0);
            $row['p_id'] = $row['p_id'] ?? 0;
            $row['project_id'] = $data['project_id'] ?? 0;
            $row['description'] = $data['description'] ?? '';
            return $row;
        }, $data['task_list'] ?? []);
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
        $result = self::$model
            ->field([
                'task.*',
                'projects.name as projects_name',
                'projects.number as projects_number',
                'company.name as company_name',
                'personnel.name as personnel_name',
                'personnel.phone as personnel_phone',
            ])
            ->leftJoin('projects', 'projects.id = task.project_id')
            ->leftJoin('company', 'company.id = task.company_id')
            ->leftJoin('personnel', 'personnel.id = task.personnel_id')
            ->where(['task.id' => $id])
            ->find()
            ->toArray();
        if ($result) {
            $result['program_list'] = Program::getView($task_id = $id, self::TYPE_TASK);
            $result['accessory_list'] = Accessory::getView($task_id, self::TYPE_TASK);
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

        $programList = $param['program_list'] ?? [];
        $accessoryList = $param['accessory_list'] ?? [];

        $program = new Program();
        $accessory = new Accessory();

        Db::startTrans();
        try {
            $result = self::$model
                ->isUpdate(true)
                ->save($param);
            !$result && exception('UPDATE_FAIL');
            $program->saveData($programList, self::TYPE_TASK, $taskId = $id, $param['company_id']);
            $accessory->saveData($accessoryList, self::TYPE_TASK, $taskId, $param['company_id']);
            /*提交事务*/
            Db::commit();
        } catch (\Exception $e) {
            /*回滚事务*/
            Db::rollback();
            return returnFalse(lang('UPDATE_FAIL'), $param, self::PARAM_FLAG);
        }
        return returnTrue(lang('UPDATE_SUCCESS'), $param, self::PARAM_FLAG);
    }

    public function stopTask($id)
    {
        $input = $this->request;
        $param = $input->param();

        $result = self::$model
            ->isUpdate(true)
            ->save(['id' => $id, 'status' => 7]);

        if ($result) {
            return returnTrue(lang('PROJECT_STOP'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('PROJECT_STOP_FAIL'), $param, self::PARAM_FLAG);
        }
    }

    public function updateTaskProgress($task_id)
    {
        $input = $this->request;
        $param = $input->param();
        $progress = $param['progress'] ?? 0;
        $result = self::$model
            ->isUpdate(true)
            ->save(['id' => $task_id, 'schedule' => $progress, 'status' => 4]);

        if ($result) {
            return returnTrue(lang('PROJECT_PROGRESS_UPDATE'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('PROJECT_PROGRESS_UPDATE_FAIL'), $param, self::PARAM_FLAG);
        }
    }

    public function refuseTask($task_id)
    {
        $input = $this->request;
        $param = $input->param();
        $result = self::$model
            ->isUpdate(true)
            ->save(['id' => $task_id, 'schedule' => 0, 'status' => 1]);

        if ($result) {
            return returnTrue(lang('REFUSE_TASK'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('REFUSE_TASK_FAIL'), $param, self::PARAM_FLAG);
        }
    }

    public function acceptTask($task_id)
    {
        $input = $this->request;
        $param = $input->param();
        $result = self::$model
            ->isUpdate(true)
            ->save(['id' => $task_id, 'schedule' => 0, 'status' => 4]);

        if ($result) {
            return returnTrue(lang('ACCEPT_TASK'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('ACCEPT_TASK_FAIL'), $param, self::PARAM_FLAG);
        }
    }

    /**
     * 批量修改任务
     * @return array
     * @throws Exception
     */
    public function taskBatch()
    {
        $input = $this->request;
        $param = $input->param();

        $result = self::$model
            ->saveAll($param);
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
