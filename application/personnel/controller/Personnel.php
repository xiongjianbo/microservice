<?php

namespace app\personnel\controller;

use think\Db;
use think\db\Where;
use think\Request;
use app\common\model\Department;

class Personnel
{
    /**
     * @var \app\common\model\Personnel
     */
    private $personnel;

    private $param;

    private $userInfo;

    /**
     * Personnel constructor.
     * @param \app\common\model\Personnel $personnel
     * @param Request $request
     */
    public function __construct(\app\common\model\Personnel $personnel, Request $request)
    {
        $this->personnel = $personnel;
        $this->param = $request->param();
        $this->userInfo = $this->param['userInfo'];
        unset($this->param['userInfo']);
    }

    /**
     * 获取列表
     *
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $where = new Where;
        $where['type'] = ['in', $this->param['type']];
        $where['company_id'] = ['=', $this->param['company_id']];
        if (!empty($this->param['name'])) {
            $where['name'] = ['like', '%' . $this->param['name'] . '%'];
        }
        if ($this->param['ruleAuth'] == 2) {
            $department_id = $this->userInfo->department_id;
        }
        if (!empty($this->param['department_id'])) {
            $department_id = $this->param['department_id'];
        }
        if (!empty($department_id)) {
            $departmentIds = (new Department())->getChildrenIds($department_id);
            $where['department_id'] = ['in', $departmentIds];

        }

        $data = $this->personnel->where($where->enclose())->field('password', true)->paginate($this->param['limit']);
        return jsonResponse($data);
    }

    /**
     * 获取详情
     *
     * @param $id
     * @return \think\response\Json
     */
    public function show($id)
    {
        $data = $this->personnel->with('personnelInfo')->get($id);
        return jsonResponse($data);
    }

    /**
     * 新增
     *
     * @return $this|\think\response\Json
     */
    public function store()
    {
        $this->param['password'] = passwordMd5($this->param['username']);

        Db::startTrans();
        try {
            $this->personnel->allowField(true)->save($this->param);
            $this->param['personnel_id'] = $this->personnel->id;
            $this->personnel->personnelInfo()->allowField(true)->save($this->param);

            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonResponse([], '新增失败', 301);
        }
        return jsonResponse();
    }

    /**
     * 修改
     *
     * @param $id
     * @return $this|\think\response\Json
     */
    public function update($id)
    {
        if (!empty($this->param['password'])) {
            $this->param['password'] = passwordMd5($this->param['password']);
        }

        Db::startTrans();
        try {
            $this->personnel->allowField(true)->save($this->param, ['id' => $id]);
            $this->personnel->personnelInfo()->allowField(true)->save($this->param, ['personnel_id' => $id]);
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonResponse([], '修改失败', 301);
        }
        return jsonResponse();
    }

    /**
     * 员工离职
     *
     * @param $id
     * @return $this|\think\response\Json
     */
    public function destroy($id)
    {
        $result = $this->personnel->save(['type', -1], ['id' => $id]);
        if (false === $result) {
            return jsonResponse([], '设置离职失败', 301);
        }
        return jsonResponse();
    }


    /**
     * 修改个人信息
     *
     * @return $this|\think\response\Json
     */
    public function updateSelf()
    {
        if (!empty($this->param['password'])) {
            $this->param['password'] = passwordMd5($this->param['password']);
        }
        $id = $this->userInfo->id;

        Db::startTrans();
        try {
            $this->personnel->allowField(true)->save($this->param, ['id' => $id]);
            $this->personnel->personnelInfo()->allowField(true)->save($this->param, ['personnel_id' => $id]);
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonResponse([], '修改失败', 301);
        }
        return jsonResponse();
    }


}
