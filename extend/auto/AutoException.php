<?php
/**
 * Created by PhpStorm.
 * User: i
 * Date: 2018/10/23
 * Time: 10:55
 */

namespace auto;

use Exception;
use think\exception\Handle;
use think\Container;

class AutoException extends Handle
{
    private static $debug;

    public function __construct()
    {
        self::$debug = config('app_debug', false);
        self::$debug = true;
    }

    public function render(Exception $e)
    {
        return json([
            'status' => false,
            'code' => empty($e->getCode()) ? '002' : $e->getCode(),
            'message' => $e->getMessage(),
            'data' => !self::$debug ? '' : [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
        ]);
    }

    protected function getMessage(Exception $exception)
    {
        $message = $exception->getMessage();
        if (PHP_SAPI == 'cli') {
            return $message;
        }

        $lang = Container::get('lang');

        if (strpos($message, ':')) {
            $name = strstr($message, ':', true);
            if ($lang->has($name)) {
                $other = self::$debug ? strstr($message, ':') : '';
                $message = $lang->get($name) . $other;
            }
        } elseif (strpos($message, ',')) {
            $name = strstr($message, ',', true);
            if ($lang->has($name)) {
                $other = self::$debug ? ':' . substr(strstr($message, ','), 1) : '';
                $message = $lang->get($name) . $other;
            }
        } elseif ($lang->has($message)) {
            $message = $lang->get($message);
        }

        return $message;
    }
}