<?php

namespace app\asset\controller;

use think\db\Where;
use think\Request;

class Supplier
{

    private $param;

    private $supplier;

    public function __construct(Request $request, \app\asset\model\Supplier $supplier)
    {
        $this->param = $request->param();
        $this->supplier = $supplier;
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
        $where['company_id'] = ['=', $this->param['company_id']];
        if (!empty($this->param['name'])) {
            $where['name'] = ['like', '%' . $this->param['name'] . '%'];
        }
        if (!empty($this->param['abbreviation'])) {
            $where['abbreviation'] = ['like', '%' . $this->param['abbreviation'] . '%'];
        }

        $data = $this->supplier->where($where->enclose())->paginate($this->param['limit']);
        return jsonResponse($data);
    }

    /**
     * 新增
     *
     * @return $this|\think\response\Json
     */
    public function store()
    {
        $result = $this->supplier
            ->allowField(['name', 'abbreviation', 'address', 'type', 'tel', 'email', 'contact', 'company_id'])
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
        $data = $this->supplier->with('supplementPurchase.personnel')->get($id);
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
        $supplier = $this->supplier->get($id);
        if (!$supplier) {
            return jsonResponse([], '数据不存在', 404);
        }
        $result = $supplier
            ->allowField(['name', 'abbreviation', 'address', 'type', 'tel', 'email', 'contact'])
            ->save($this->param);
        if (false === $result) {
            return jsonResponse([], '修改失败', 301);
        }
        return jsonResponse();
    }
}
