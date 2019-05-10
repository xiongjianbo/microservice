<?php
/**
 * Created by PhpStorm.
 * User: i
 * Date: 2018/10/16
 * Time: 10:55
 */

namespace auto;

use \Exception as E;

trait Check
{
    public function check($data, $scene = '', $validate_name = '')
    {
        if ($validate_name) {
            $validate = validate($validate_name);
        } else {
            $validate = validate($this->getName());
        }
        !empty($scene) && $validate->scene($scene);
        $check = $validate->check($data);
        if (!$check) {
            throw new E($validate->getError());
        }
        return $this;
    }

    public function checkAll($data, $scene = '', $validate_name = '')
    {
        foreach ($data as $row) {
            $this->check($row, $scene);
        }
        return $this;
    }

    public function back($message = '', $status = true, $flag = 'data')
    {
        //定义返回的参数
        $result = [];

        // 1.如果$this不存在则为[]
        $result = $this ?: [];

        // 2.转换为数组
        if (is_object($result)) {
            $result = $result->toArray();
        }

        // 3.消息处理
        if ($message == '') {
            $message = $status ? '成功' : '失败';
        }

        // 4. code
        $code = $status ? '200' : '400';

        return [
            $flag => $result,
            'status' => $status,
            'code' => $code,
            'msg' => $message,
        ];
    }


    public static function staticCheck($data, $scene = '', $validate_name = '')
    {
        if ($validate_name) {
            $validate = validate($validate_name);
        } else {
            $validate = validate((new self)->getName());
        }
        !empty($scene) && $validate->scene($scene);
        $check = $validate->check($data);
        if (!$check) {
            throw new E($validate->getError());
        }
        return new self;
    }

}