<?php

namespace app\project\controller;

use app\common\model\Accessory;
use app\common\model\AuditRecord;
use app\common\model\ProjectDelivery;
use app\common\model\Projects;
use audit\Audit;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class ProjectDeliveryController extends Controller
{

    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, ProjectDelivery $model)
    {
        parent::__construct($app);

        self::$model = $model;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = self::$model->getList($this->request);
        return returnTrue(lang('SELECT_SUCCESS'), $data);
    }

    /**
     * 保存新建或更新的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $param = $request->except(['status']);
        $accessoryList = $param['accessory_list'] ?? [];
        $projectDelivery = new ProjectDelivery();
        $where = [];
        $scene = 'add';
        //编辑
        if (isset($param['id'])) {
            //如果状态是已通过，则不能编辑
            $projectDelivery = self::$model->find($param['id']);
            if ($projectDelivery->status == ProjectDelivery::STATUS_PASS) {
                return returnFalse(lang('SAVE_FAIL'), lang('PASSED_AUDIT_CAN_NOT_BE_CHANGE'), self::PARAM_FLAG);
            }
            $where['id'] = $param['id'];
            $scene = 'edit';
        }

        Db::startTrans();
        try {
            //存储到项目交付表
            $projectDelivery
                ->check($param, $scene)
                ->save($param, $where);

            //获取关联项目的公司id，用于写入附件表
            $company_id = $projectDelivery->projects->company_id;
            //保存到附件表
            $accessory = new Accessory();
            $accessory->saveData($accessoryList, Accessory::TYPE_DELIVERY, $projectDelivery->id, $company_id);
            //如果是修改，记录日志，并将状态重置为待验收
            if (isset($param['id'])) {
                ProjectDelivery::where('id', $param['id'])->update(['status' => 0]);
            }
            Db::commit();
            return returnTrue(lang('SAVE_SUCCESS'), $param, self::PARAM_FLAG);
        } catch (Exception $e) {
            Db::rollback();
            return returnFalse(lang('SAVE_FAIL'), $e->getMessage(), self::PARAM_FLAG);
        }

    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $result = self::$model->getById($id);
        if (!empty($result)) {
            return returnTrue(lang('SELECT_SUCCESS'), $result);
        } else {
            return returnFalse(lang('NO_DATA'), $result);
        }
    }


    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
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

    //客户验收审核
    public function audit(Request $request)
    {
        $param = $request->only(['id', 'status', 'audit_comment']);
        self::$model->check($param,'audit');

        $projectDelivery=ProjectDelivery::get($param['id']);

        //如果状态是已通过，则不能编辑
        if ($projectDelivery->status == ProjectDelivery::STATUS_PASS) {
            return returnFalse(lang('SAVE_FAIL'), lang('PASSED_AUDIT_CAN_NOT_BE_CHANGE'), self::PARAM_FLAG);
        }

        Db::startTrans();
        try {
            //保存审核结果到交付表
            $result = $projectDelivery
                ->save($param);

            //如果是拒绝，则记录日志 todo

            //修改项目状态
            $projectStatus = $param['status'] == ProjectDelivery::STATUS_REFUSE ? 3 : 4;
            Projects::where('id',$projectDelivery->project_id)->update(['status'=>$projectStatus]);

            Db::commit();
            return returnTrue(lang('ADD_SUCCESS'), $param, self::PARAM_FLAG);

        } catch (Exception $e) {
            Db::rollback();
            return returnFalse(lang('SAVE_FAIL'), $e->getMessage(), self::PARAM_FLAG);
        }


    }

}
