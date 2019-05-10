<?php

namespace app\personnel\controller;

use think\Db;
use think\Request;

class Department
{
    /**
     * @var \app\common\model\Department
     */
    protected $department;

    /**
     * @var mixed
     */
    protected $param;

    public function __construct(\app\common\model\Department $department, Request $request)
    {
        $this->department = $department;
        $this->param = $request->param();
        unset($this->param['userInfo']);
    }

    /**
     * 获取列表
     *
     * @return \think\response\Json
     */
    public function index()
    {
        $model = $this->department;
        if (!empty($this->param['with'])) {
            switch ($this->param['with']) {
                case 'position':
                    $model = $model->with(['position' => function ($query) {
                        $query->where('status', 1)->field('id,title,status,department_id');
                    }]);
                    break;
                case 'positionPersonnel':
                    $model = $model->with(['position' => function ($query) {
                        $query->where('status', 1)->field('id,title,status,department_id')
                            ->with(['personnel' => function ($query) {
                                $query->where('type', 1)->field('id,name,position_id');
                            }]);
                    }]);
                    break;
                case 'personnel':
                    $model = $model->with(['personnel' => function ($query) {
                        $query->where('type', 1)->field('id,name,department_id');
                    }]);
                    break;
                default:
                    break;
            }
        }

        $data = $model->where('company_id', $this->param['company_id'])->select()->toArray();
        $data = listToTree($data);
        return jsonResponse($data);
    }

    /**
     * 新增
     *
     * @return $this|\think\response\Json
     */
    public function store()
    {
        $result = $this->department->allowField(['p_id', 'name', 'path', 'company_id'])->save($this->param);
        if (!$result) {
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
        $department = $this->department->get($id);
        if (!$department) {
            return jsonResponse([], '数据不存在', 404);
        }
        $result = $department->allowField(['p_id', 'name', 'path'])->save($this->param);
        if (false === $result) {
            return jsonResponse([], '修改失败', 301);
        }
        return jsonResponse();
    }

    /**
     * 删除
     *
     * @param $id
     * @return $this|\think\response\Json
     */
    public
    function destroy($id)
    {
        $childrenIds = $this->department->getChildrenIds($id, false);

        if (!empty($childrenIds)) {
            return jsonResponse([], '有子部门，不能删除', 301);
        }

        $positionDepartment = DB::table('position')->where('department_id', $id)->count();
        if (!empty($positionDepartment)) {
            return jsonResponse([], '有岗位属于该部门，不能删除', 301);
        }

        $personalDepartment = DB::table('personnel')->where('department_id', $id)->count();
        if (!empty($personalDepartment)) {
            return jsonResponse([], '有员工或岗位属于该部门，不能删除', 301);
        }
        $result = $this->department->destroy($id);
        if (false === $result) {
            return jsonResponse([], '删除失败', 301);
        }
        return jsonResponse();
    }

}
