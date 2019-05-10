<?php

namespace app\auth\controller;

use app\common\model\Personnel;
use think\Request;

class Auth
{
    /**
     * @var Personnel
     */
    private $personnel;

    private $param;

    /**
     * Auth constructor.
     * @param Personnel $personnel
     * @param Request $request
     */
    public function __construct(Personnel $personnel, Request $request)
    {
        $this->personnel = $personnel;
        $this->param = $request->param();
        $this->token = $request->header('token', '');
    }

    public function login()
    {
        $personnel = $this->personnel->where('username', $this->param['username'])
            ->with('position,company')
            ->find();
        if (!$personnel) {
            return jsonResponse([], '账号不存在', 404);
        }
        if ($personnel->password !== passwordMd5($this->param['password'])) {
            return jsonResponse([], '密码不正确', 401);
        }
        if ($personnel->type < 0) {
            return jsonResponse([], '非公司员工，如有疑问请联系管理员', 403);
        }
        if (empty($personnel->position) && $personnel->type > 0) {
            return jsonResponse([], '当前账号无职位，请联系管理员', 403);
        }
        $rule = $this->getRule($personnel);
        if (!is_array($rule)) {
            return jsonResponse([], '当前账号无权限，请联系管理员', 403);
        }
        unset($personnel->password);
        unset($personnel->company->rules);
        if (!empty($personnel->position->rules)) {
            unset($personnel->position->rules);
        }
        // 保存缓存
        $info['userInfo'] = $personnel;
        $info['token'] = passwordMd5($personnel['username']);
        $info['_AUTH_LIST_'] = $rule;
        cache('Auth_' . $info['token'], $info);
        // 返回信息
        return jsonResponse($info);
    }

    /**
     * 退出登录
     *
     * @return \think\response\Json
     */
    public function logout()
    {
        cache('Auth_' . $this->token, null);
        // 返回信息
        return jsonResponse();
    }

    /**
     * 获取用户拥有规则
     *
     * @param $personnel
     * @return array|mixed
     */
    private function getRule($personnel)
    {
        if ($personnel->type == 0) {
            $rules = json_decode($personnel->company->rules, true);
        } else {
            $rules = json_decode($personnel->position->rules, true);
        }
        return $rules;
    }


}
