<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class RmLang extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('rm:lang');

        // 设置参数
        $this->setDescription('这个指令用来删除语言包 例：php think rm:lang en');
        $this->setHelp('这个指令用来删除语言包 例：php think rm:lang en');
        $this->addArgument('lang', null, "langName");
        
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $lang = $input->getArgument('lang');
        $appPath = env('APP_PATH');
        $ds = DIRECTORY_SEPARATOR;
        $filePath = "{$appPath}lang{$ds}{$lang}.php";

        //这里必须用@屏蔽，因为如果无文件操作权限，可能会抛出异常
        @unlink($filePath);
        $output->writeln("删除语言包成功");
    }
}
