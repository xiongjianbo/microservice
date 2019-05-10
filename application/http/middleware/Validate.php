<?php

namespace app\http\middleware;


use think\Container;
use think\exception\ValidateException;

class Validate
{
    public function handle($request, \Closure $next)
    {
        $param = $request->param();

        $result = $this->validate($param, $request->controller() . '.' . $request->action());

        if (true !== $result) {
            // 验证失败，返回错误信息
            return jsonResponse([], $result, 403);
        }
        return $next($request);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array $data 数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array $message 提示信息
     * @param  bool $batch 是否批量验证
     * @param  mixed $callback 回调方法（闭包）
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate($data, $validate, $message = [], $batch = false, $callback = null)
    {
        if (is_array($validate)) {
            $v = Container::get('app')->validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $v = Container::get('app')->validate($validate);
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }


        if (is_array($message)) {
            $v->message($message);
        }

        if ($callback && is_callable($callback)) {
            call_user_func_array($callback, [$v, &$data]);
        }

        if (!$v->check($data)) {
            return $v->getError();
        }

        return true;
    }
}
