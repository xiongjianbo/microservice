<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use \PDO;

class MigrateFromDb extends Command
{
    public static $conn;
    public static $database;
    public static $db_prefix;
    protected function configure()
    {
        // 指令配置
        $this->setName('migrate:from-mysql');
        // 设置参数
        $this->setDescription('这个指令使用MYSQL来生成数据迁移文件 例：php think migrate:from-mysql');
        $this->setHelp('这个指令使用MYSQL来生成数据迁移文件 例：php think migrate:from-mysql');
        $this->addArgument('table', null, "tableName");
        $this->addOption('all', 'a', null, '操作所有表');

        self::$db_prefix = config('database.prefix');
    }

    protected function execute(Input $input, Output $output)
    {
        $all = $input->getOption('all');
        $tableName = $input->getArgument('table');

        if ($all || !$tableName) {
            $this->all();
        } else {
            $this->one($tableName);
        }
    }

    protected function all()
    {
        $tables = $this->getAllTables();
        $timetag = date('YmdHis');
        $ds = DIRECTORY_SEPARATOR;
        $root_path = env('ROOT_PATH');
        $savePath = "{$root_path}database{$ds}";

        //清空所有迁移记录
        $this->clearMigrateTable();

        //备份migrates的所有文件
        if (!is_dir($back_dir = "{$savePath}back_$timetag{$ds}")) {
            rename($old_dir = "{$savePath}migrations", $back_dir);
            mkdir($old_dir, 0777, true);
        }

        $prefix = self::$db_prefix;

        foreach ($tables as $table) {
            //pmc_migrations表不参与
            if ($table == "{$prefix}migrations") {
                continue;
            }
            //表注释
            $comment = $this->getTableComment($table);
            //表名语句
            $no_prefix_table = $this->replaceTableName($table);
            $context = '$this->table(\'' . $no_prefix_table . '\')' . "\n";
            //生成表注释
            $context .= "        ->setComment('{$comment}')\n";
            //生成表结构
            $context .= $this->getTableStructureString($table);
            //创建
            $context .= '        ->create();';
            $timetag = date('YmdH') . rand(1000, 9999);
            $this->replaceAndSave($no_prefix_table, $context, "{$savePath}migrations{$ds}{$timetag}_{$no_prefix_table}.php");
        }
    }

    protected function replaceTableName($tableName = '')
    {
        $start_pos = strlen(self::$db_prefix);
        return substr($tableName, $start_pos);
    }

    protected function clearMigrateTable()
    {
        $prefix = self::$db_prefix;
        $sql = "TRUNCATE TABLE `{$prefix}migrations`";
        self::$conn->query($sql);
    }

    protected function getAllTables()
    {
        self::$conn = isset(self::$conn) ? self::$conn : $this->getConn();
        $sql = "SHOW TABLES;";
        return self::$conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getTableStructureString($tableName = '')
    {
        $fields = $this->getTableStructure($tableName);
        $string = '';
        foreach ($fields as $field) {
            //跳过ID字段
            if ($field['Field'] === 'id') {
                continue;
            }
            list($fieldName, $type, , $null, , $default, , , $comment) = $field;
            list($field_type, $field_len) = $this->getFieldType($type);

            $limit = $field_len ? "'limit' => {$field_len}, " : '';
            $null = $null=='YES' ? "'null' => true, " : '';

            //货币类型单独处理
            $precision = '';
            $scale = '';
            if (in_array($field_type, ['decimal'])) {
                $limit = '';
                list($precision, $scale) = explode(',', $field_len);
            }

            //enum单独处理
            $values = '';
            if ($field_type === 'enum') {
                $values = $field_len ? "'values' => {$field_len}, " : '';
                $limit = '';
            }

            $precision = $precision ? "'precision' => {$precision}, " : '';
            $scale = $scale ? "'scale' => {$scale}, " : '';
            $default = $default ? "'default' => '{$default}', " : '';

            $string .= "        ->addColumn('{$fieldName}', '{$field_type}', [{$precision}{$scale}{$null}{$limit}{$values}{$default}'comment' => '{$comment}'])\n";
        }
        return $string;
    }

    protected function getFieldType($fieldstring = '')
    {
        /**
         *  处理int(11) unsigned无符号情况，无符号不处理
         *  $fieldstring = "int(11) unsigned";
         */
        $endPos = stripos($fieldstring, ')');
        $endPos = $endPos ? $endPos + 1 : strlen($fieldstring);
        $fieldstring = substr($fieldstring, 0, $endPos);

        $startPos = stripos($fieldstring, '(');
        $startPos = $startPos == null ? strlen($fieldstring) : $startPos;
        $type = substr($fieldstring, 0, $startPos);
        $len = substr($fieldstring, $startPos + 1, -1);

        switch ($type) {
            case 'int':
                $type = 'integer';
                break;
            case 'bigint':
                $type = 'biginteger';
                break;
            case 'tinyint':
                $type = 'integer';
                $len = '\Phinx\Db\Adapter\MysqlAdapter::INT_TINY';
                break;
            case 'smallint':
                $type = 'integer';
                $len = '\Phinx\Db\Adapter\MysqlAdapter::INT_SMALL';
                break;
            case 'date':
                $type = 'date';
                $len = '';
                break;
            case 'time':
                $type = 'time';
                break;
            case 'decimal':
                $type = 'decimal';
                break;
            case 'varchar':
                $type = 'string';
                break;
            case 'char':
                $type = 'string';
                break;
            case 'enum':
                $type = 'enum';
                $len = "[{$len}]";
                break;
            default:
                break;
        }
        return [
            $type,
            $len
        ];
    }

    protected function getTableStructure($tableName = '')
    {
        self::$conn = isset(self::$conn) ? self::$conn : $this->getConn();
        $sql = "SHOW FULL FIELDS FROM {$tableName}";
        return self::$conn->query($sql)->fetchAll(PDO::FETCH_BOTH);
    }

    protected function getTableComment($tableName)
    {
        self::$conn = isset(self::$conn) ? self::$conn : $this->getConn();
        $db = self::$database;
        $sql = "SELECT table_comment FROM information_schema.TABLES WHERE table_schema = '{$db}' AND table_name='{$tableName}'";
        return self::$conn->query($sql)->fetchColumn(0);
    }

    protected function getConn()
    {
        self::$database = config('database.database');
        $connect = new PDO("mysql:host=" . config('database.hostname') . ";dbname=" . self::$database, config('database.username'), config('database.password'));
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;
    }

    protected function one($tableName)
    {
        if (!$tableName) {
            echo '表名为空', "\n";
        }

    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'migrate.stub';
        return file_get_contents($stubPath);
    }

    protected function replaceAndSave($tableName, $content, $saveFile = '')
    {
        $tableName = $this->camelize($tableName);
        $contents = $this->read();
        $contents = str_replace('{%tableName%}', $tableName, $contents);
        $contents = str_replace('{%Content%}', $content, $contents);

        if (!file_exists($saveFile)) {
            file_put_contents($saveFile, $contents);
            echo "创建Migrate文件:{$tableName}成功", "\n";
        } else {
            echo "{$tableName}已存在", "\n";
        }
    }

    function camelize($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }
}
