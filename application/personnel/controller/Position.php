<?php

namespace app\personnel\controller;

use think\Db;
use think\Request;

class Position
{
    /**
     * @var \app\common\model\Position
     */
    protected $position;

    /**
     * @var mixed
     */
    protected $param;


    /**
     * Position constructor.
     * @param \app\common\model\Position $position
     * @param Request $request
     */
    public function __construct(\app\common\model\Position $position, Request $request)
    {
        $this->position = $position;
        $this->param = $request->param();
        unset($this->param['userInfo']);
    }


    /**
     * 获取规则列表
     *
     * @return $this|\think\response\Json
     */
    public function rule()
    {
        $data = Db::table('rule')->select();
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
        $result = $this->position
            ->allowField(['title', 'rules', 'department_id','description', 'company_id'])
            ->save($this->param);
        if (!$result) {
            return jsonResponse([], '新增失败', 301);
        }
        return jsonResponse();
    }

    /**
     * 获取详情
     *
     * @param $id
     * @return \think\response\Json
     */
    public function show($id)
    {
        $data = $this->position->get($id);
        return jsonResponse($data);
    }

    /**
     * 修改
     *
     * @param $id
     * @return $this|\think\response\Json
     */
    public function update($id)
    {
        $position = $this->position->get($id);
        if (!$position) {
            return jsonResponse([], '数据不存在', 404);
        }

        // 启动事务
        Db::startTrans();
        try {
            if ($position->department_id != $this->param['department_id']) {
                Db::table('personnel')->where('position_id', $position->id)
                    ->update(['department_id' => $this->param['department_id']]);
            }
            $position->allowField(['title', 'rules', 'department_id', 'company_id', 'description'])->save($this->param);

            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
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
    public function destroy($id)
    {
        $personalPosition = db('personnel')->where('position_id', $id)->count();
        if (!empty($personalPosition)) {
            return jsonResponse([], '有员工属于该职位，不能删除', 301);
        }

        $result = $this->position->destroy($id);
        if (false === $result) {
            return jsonResponse([], '删除失败', 301);
        }
        return jsonResponse();
    }

}
