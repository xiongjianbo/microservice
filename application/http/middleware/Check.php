<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        $route = $request->module() . '-' . $request->controller() . '-' . $request->action();
        if ($request->userInfo->type == 0) {
            if (!in_array($route, $request->authList)) {
                // 验证失败，返回错误信息
                return jsonResponse([], '权限不足', 401);
            }
            $request->ruleAuth = 1;
        } else {
            if (empty($request->authList[$route])) {
                // 验证失败，返回错误信息
                return jsonResponse([], '权限不足', 401);
            }
            $request->ruleAuth = $request->authList[$route];
        }
        return $next($request);
    }
}
