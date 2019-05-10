<?php

namespace app\project\controller;

use think\App;
use think\Controller;
use think\Request;
use \app\common\model\SkillCategory;
use \Exception;
use \think\exception\DbException;

class SkillCategoryController extends Controller
{
    protected static $model;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, SkillCategory $model)
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
        $name = $input->get('name', '');

        $whereOr = [];
        if ($name) {
            $where['name'] = $name;
            $whereOr['name_en'] = $name;
            $whereOr['name_ko'] = $name;
            $whereOr['name_ja'] = $name;
        }

        $data = self::$model
            ->where($where)
            ->whereOr($whereOr)
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


    /**
     * 获取键值对
     * @return array
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function pair()
    {
        $where = [];

        $data = self::$model
            ->where($where)
            ->field('id,name,name_en,name_ko,name_ja')
            ->select()
            ->toArray();
        return returnTrue(lang('SELECT_SUCCESS'), $data);
    }
}