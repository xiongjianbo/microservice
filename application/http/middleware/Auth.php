<?php

namespace app\http\middleware;

class Auth
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed|\think\response\Json
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->header('token');

        $info = cache('Auth_' . $token);

        if (empty($token) || empty($info)) {

            return jsonResponse([], '请先登录', 401);
        }
        $request->userInfo = $info['userInfo'];
        $request->authList = $info['_AUTH_LIST_'];
        $request->limit = $request->param('limit',10);
        $request->company_id = $request->userInfo->company->id;
        return $next($request);
    }

}
