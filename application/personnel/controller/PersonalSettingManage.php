<?php

namespace app\personnel\controller;

use think\App;
use think\Controller;
use think\Request;
use \app\personnel\model\PersonalSettings;
use \think\db\exception\DataNotFoundException;
use \think\db\exception\ModelNotFoundException;
use \think\exception\DbException;

class PersonalSettingManage extends Controller
{
    protected static $model;
    protected static $personnel_id;
    const PARAM_FLAG = 'param';

    public function __construct(App $app, PersonalSettings $model, Request $request)
    {
        parent::__construct($app);
        $this->param = $request->param();
        $this->userInfo = $this->param['userInfo'];
        self::$model = $model;
        /*todo persionel_id用于获取已经登陆的用户ID,做测试时不接入中间件*/
        self::$personnel_id = $this->userInfo['id'];

    }

    /**
     * @param $param
     * @return PersonalSettings|array|\PDOStatement|string|\think\Model
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function findPersonnelSetting($param)
    {
        $where = [
            'personnel_id' => self::$personnel_id ?? 0,
        ];
        $personnel_setting = self::$model->where($where)->findOrEmpty();
        if ($personnel_setting->isEmpty()) {
            $param['personnel_id'] = self::$personnel_id ?? 0;
            self::$model->allowField('is_welcome,is_news,is_task,is_schedule,is_open,secondary_password,personnel_id')->save($param);
            return self::$model;
        }
        return $personnel_setting;
    }

    /**
     * 设置is_open = 0时，必须输入二级密码
     * @param $personnel_setting
     * @param $param
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function level2PassSet($personnel_setting, $param)
    {
        $condition1 = isset($param['is_open']) && $param['is_open'] == 0;
        $condition2 = (int)$personnel_setting['is_open'] == 1 && !isset($param['secondary_password']);
        $condition1 && $condition2 && exception('LEVEL2_PASSWORD_REQUIRED');
        /*检查密码是否有问题*/
        if ($condition1 && isset($param['secondary_password'])) {
            $where = [
                'personnel_id' => self::$personnel_id ?? 0,
                'secondary_password' => md5($param['secondary_password']),
            ];
            $result = self::$model->where($where)->findOrEmpty()->toArray();
            count($result) == 0 && exception('LEVEL2 PASSWORD ERROR');
        }
    }

    /**
     * 个人设置
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function set()
    {
        $input = $this->request;
        $param = $input->param();
        /*1.如果不存在记录则新建记录*/
        $personnel_setting = $this->findPersonnelSetting($param);
        $this->level2PassSet($personnel_setting, $param);
        /*2.允许对每一项单独设置*/
        /*已解决-----BUG:(当数据库中is_open等于0，同时查询密码不为空，客户开启时，没有判断密码)*/
        if ($personnel_setting['is_open'] == 0 && !empty($personnel_setting['secondary_password'])) {
            if (md5($param['secondary_password']) == $personnel_setting['secondary_password']) {
                $personnel_setting
                    ->allowField('is_welcome,is_news,is_task,is_schedule,is_open')
                    ->save($param);
            } else {
                return jsonResponse([], lang('LEVEL2 PASSWORD ERROR'), 301);
            }
        }
        isset($param['secondary_password']) && $param['secondary_password'] = md5($param['secondary_password']);
        $result = $personnel_setting
            ->allowField('is_welcome,is_news,is_task,is_schedule,is_open,secondary_password')
            ->save($param);

        /*3.返回请求结果，将请求的参数也返回*/
        if ($result) {
            return returnTrue(lang('SET_SUCCESS'), $param, self::PARAM_FLAG);
        } else {
            return returnFalse(lang('SET_FAIL'), $param, self::PARAM_FLAG);
        }
    }
}