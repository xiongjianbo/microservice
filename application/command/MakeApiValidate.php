<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use \PDO;

class MakeApiValidate extends Command
{
    public static $conn;
    public static $database;
    public static $db_prefix;

    protected function configure()
    {
        // 指令配置
        $this->setName('make:api-validate');
        // 设置参数
        $this->setDescription('这个指令用来创建API验证 例：php think make:api-validate Customer');
        $this->setHelp('这个指令用来创建API验证 例：php think make:api-validate Customer');
        $this->addArgument('validate', null, "validateName");

        self::$db_prefix = config('database.prefix');
    }

    protected function execute(Input $input, Output $output)
    {
        $validate_name = $input->getArgument('validate');
        $this->replaceAndSave($validate_name);
    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'validate.stub';
        return file_get_contents($stubPath);
    }

    protected function replaceAndSave($validate_name)
    {
        $appPath = env('APP_PATH');
        $contents = $this->read();
        $ds = DIRECTORY_SEPARATOR;
        $rule_list = $this->getRule($validate_name);
        $contents = str_replace('{%ValidateName%}', $validate_name, $contents);
        $contents = str_replace('{%RuleList%}', $rule_list, $contents);

        $savePath = "{$appPath}common{$ds}validate{$ds}";

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        if (!file_exists($file = $savePath . "{$validate_name}.php")) {
            file_put_contents($file, $contents);
            echo "创建API验证:{$validate_name}成功", "\n";
        } else {
            echo "{$validate_name}已存在", "\n";
        }
    }

    protected function getConn()
    {
        self::$database = config('database.database');
        $connect = new PDO("mysql:host=" . config('database.hostname') . ";dbname=" . self::$database, config('database.username'), config('database.password'));
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;
    }

    protected function getTableStructure($tableName = '')
    {
        self::$conn = isset(self::$conn) ? self::$conn : $this->getConn();
        $sql = "SHOW FULL FIELDS FROM `{$tableName}`";
        return self::$conn->query($sql)->fetchAll(PDO::FETCH_NUM);
    }

    protected function makeTypeLen($format_string = '')
    {
        $out_array = ['enum', 'set'];
        $f = preg_split("/[\(\)\',]+/", $format_string);
        count($f) == 1 && $f[] = '';

        list($type, $len) = $f;
        if (in_array($type, $out_array)) {
            $len = '';
        }
        return [$type, $len];
    }

    protected function getMysqlFiedsInfo($tableName = '')
    {
        $prefix = self::$db_prefix;
        $info = $this->getTableStructure("{$prefix}{$tableName}");
        return array_map(function ($row) {
            list($fields, $format) = $row;
            $result = $this->makeTypeLen($format);
            return array_merge([$fields], $result);
        }, $info);
    }

    protected function getTimestampRule($type)
    {
        $intArray = ['date', 'datetime', 'timestamp',];
        return in_array($type, $intArray) ? "            'date',\n" : '';
    }

    protected function getIntRule($type)
    {
        $intArray = ['int', 'tinyint', 'smallint', 'bigint', 'mediumint'];
        return in_array($type, $intArray) ? "            'number',\n" : '';
    }

    protected function getMailRule($name)
    {
        $intArray = ['mail', 'email'];
        return in_array($name, $intArray) ? "            'email',\n" : '';
    }

    protected function getMax($len)
    {
        $max = "            'max:{$len}',\n";
        return $len ? $max : '';
    }

    protected function getRule($validateName)
    {
        $tableName = $this->uncamelize($validateName);
        $tableInfo = $this->getMysqlFiedsInfo($tableName);
        $ruleHtml = '';
        foreach ($tableInfo as $row) {
            list($fields, $type, $len) = $row;

            $ruleHtml .= "        '{$fields}' => [\n";
            $ruleHtml .= $this->getMax($len);
            $ruleHtml .= $this->getIntRule($type);
            $ruleHtml .= $this->getTimestampRule($type);
            $ruleHtml .= $this->getMailRule($fields);
            $ruleHtml .= "        ],\n";
        }
        return $ruleHtml;
    }

    /**
     * 转驼峰
     * @param $uncamelized_words
     * @param string $separator
     * @return string
     */
    function camelize($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }

    /**
     * 驼峰转下划线
     * @param $camelCaps
     * @param string $separator
     * @return string
     */
    function uncamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}
