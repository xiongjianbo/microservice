<?php

namespace app\command;

use PDO;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Console;

class SeedReset extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('seed:reset');
        // 设置参数
        $this->setDescription('这个指令用来重新填充数据 例：php think seed:reset');
        $this->setHelp('这个指令用来重新填充数据 例：php think seed:reset');
    }

    protected function execute(Input $input, Output $output)
    {
        $conn = new PDO("mysql:host=" . config('database.hostname') . ";dbname=" . config('database.database'), config('database.username'), config('database.password'));
        // 设置 PDO 错误模式为异常
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //获取所有表名组成的一个数组
        $sql = "SHOW TABLES;";
        $r = $conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);

        //遍历数组并清空数据
        array_map(function ($tableName) use ($conn, $output) {
            if ($tableName === 'migrations') {
                return null;
            }
            $sql = "TRUNCATE TABLE `{$tableName}`";
            $conn->query($sql);
            $output->writeln("已清空{$tableName}");
        }, $r);

        // 指令输出
        $output->writeln('重新生成数据填充');

        //执行seed:run
        $output = Console::call('seed:run');
    }
}
