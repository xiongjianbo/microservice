<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\facade\Cache;

/**
 * 返回数据格式
 *
 * @param array $data
 * @param string $message
 * @param int $code
 * @return \think\response\Json
 */
function jsonResponse($data = [], $message = '', $code = 200)
{
    return json([
        'data' => $data,
        'message' => $message,
        'code' => $code
    ])->code($code);
}

/**
 * 返回true的数据格式
 *
 * @param string $msg
 * @param array $data
 * @param string $flag
 * @param bool $status
 * @param int $code
 * @return array
 */
function returnTrue($msg = 'success', $data = [], $flag = 'data', $status = true, $code = 200)
{
    $result = [
        'status' => $status,
        'code' => $code,
        'message' => $msg,
        $flag => $data,
    ];
    if (isset($data['data'])) {
        $result = array_merge($result, $data);
    }
    return $result;
}

/**
 * 返回false的数据格式
 *
 * @param string $msg
 * @param array $data
 * @param string $flag
 * @param bool $status
 * @param int $code
 * @return array
 */
function returnFalse($msg = 'fail', $data = [], $flag = 'data', $status = false, $code = 400)
{
    return [
        'status' => $status,
        'code' => $code,
        'message' => $msg,
        $flag => $data,
    ];
}

/**
 * 计算时差
 *
 * @param $start
 * @param $end
 * @return array
 */
function timeDiff($start, $end)
{
    $time = strtotime($end) - strtotime($start);
    $result = ['above' => true];
    if ($time < 0) {
        $result['above'] = false;
    }
    $time = abs($time);
    $hour = floor($time / 3600);
    $result['diff'] = $hour . ':' . floor(($time - 3600 * $hour) / 60);
    $result['hour'] = $hour;
    return $result;
}

/**
 * curl get请求
 *
 * @param $url
 * @return mixed
 */
function httpGet($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = json_decode(curl_exec($ch), true);
    return $output;
}

/**
 * 获取假期
 *
 * @param $year
 * @param $month
 * @return array
 */
function getHoliday($year, $month)
{
    if (strlen($month) === 1) {
        $month = '0' . $month;
    }

    return Cache::remember('holidays' . $year . $month, function () use ($year, $month) {
        $holidayData = httpGet('http://www.easybots.cn/api/holiday.php?m=' . $year . $month);
        return array_map(function ($v) use ($year, $month) {
            return $year . '-' . $month . '-' . $v;
        }, array_keys($holidayData[$year . $month]));
    });

}

/**
 * 树形结构
 *
 * @param $array
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 */
function listToTree($array, $pk = 'id', $pid = 'p_id', $child = 'children', $root = 0)
{

    $items = [];
    foreach ($array as $value) {
        $items[$value[$pk]] = $value;
    }
    $tree = [];
    foreach ($items as $key => $item) {
        if (isset($items[$item[$pid]])) {
            $items[$item[$pid]][$child][] = &$items[$key];
        } else {
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}

/**
 * 树状规则表处理成 module-controller-action
 *
 * @param $data
 * @return array
 */
function rulesDeal($data)
{
    if (is_array($data)) {
        $ret = [];
        foreach ($data as $k1 => $v1) {
            $str1 = $v1['name'];
            if (isset($v1['children']) && is_array($v1['children'])) {
                foreach ($v1['children'] as $k2 => $v2) {
                    $str2 = $str1 . '-' . $v2['name'];
                    if (isset($v1['children']) && is_array($v2['children'])) {
                        foreach ($v2['children'] as $k3 => $v3) {
                            $str3 = $str2 . '-' . $v3['name'];
                            $ret[] = $str3;
                        }
                    } else {
                        $ret[] = $str2;
                    }
                }
            } else {
                $ret[] = $str1;
            }
        }
        return $ret;
    }
    return [];
}

/**
 * 密码加密
 *
 * @param $str
 * @param string $auth_key
 * @return string
 */
function passwordMd5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 格式化命令
 *
 * @param $var
 */
function pr($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

/**
 * 格式化并中断
 *
 * @param $var
 */
function prd($var)
{
    pr($var);
    die();
}

/**
 * 抛出多语言异常
 * @param string $lang
 * @throws Exception
 */
function exception($lang = 'SELECT_SUCCESS')
{
    throw new Exception(lang($lang));
}
