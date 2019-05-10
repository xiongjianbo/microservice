<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class RmFacade extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('rm:facade');

        // 设置参数
        $this->setDescription('这个指令用来删除门面 例：php think rm:facade Excel');
        $this->setHelp('这个指令用来删除门面 例：php think rm:facade Excel');
        $this->addArgument('facade', null, "facadeName");
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $facadeName = $input->getArgument('facade');
        $appPath = env('APP_PATH');
        $dir = DIRECTORY_SEPARATOR;
        $filePath = "{$appPath}facade{$dir}{$facadeName}.php";

        //这里必须用@屏蔽，因为如果无文件操作权限，可能会抛出异常
        @unlink($filePath);
        $output->writeln("删除门面成功");
    }
}
