<?php

namespace app\configure\controller;

use think\Db;
use think\facade\Cache;
use think\Request;

class Config
{
    /**
     * @var \app\common\model\Config
     */
    protected $config;

    /**
     * @var mixed
     */
    protected $param;


    /**
     * Config constructor.
     * @param \app\common\model\Config $config
     * @param Request $request
     */
    public function __construct(\app\common\model\Config $config, Request $request)
    {
        $this->config = $config;
        $this->param = $request->param();
    }

    /**
     * 获取列表
     *
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $data = $this->config->paginate(10);
        return jsonResponse($data);
    }

    /**
     * 获取详情
     *
     * @param $id
     * @return \think\response\Json
     */
    public function show($name)
    {
        $data = $this->config->where('name', $name)->find();
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
        $config = $this->config->get($id);
        if (!$config) {
            return jsonResponse([], '数据不存在', 404);
        }
        $result = $config->allowField(['value', 'description'])->save($this->param);
        if (false === $result) {
            return jsonResponse([], '修改失败', 301);
        }
        Cache::set($config->name, $config->value);
        return jsonResponse();
    }

}
