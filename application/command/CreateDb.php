<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use \PDO;

class CreateDb extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:db');
        // 设置参数
        $this->setDescription('这个指令用来创建数据库 例：php think make:db pmc');
        $this->setHelp('这个指令用来创建数据库.例：php think make:db pmc');
        $this->addArgument('db', null, "dbName");
    }

    protected function execute(Input $input, Output $output)
    {
        $dbName = $input->getArgument('db');

        $conn = new PDO("mysql:host=".config('database.hostname'), config('database.username'), config('database.password'));

        // 设置 PDO 错误模式为异常
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE `{$dbName}`";

        $r = $conn->exec($sql);

        // 指令输出
    	$output->writeln($r?"创建数据库成功":"创建数据库失败");
    }
}
