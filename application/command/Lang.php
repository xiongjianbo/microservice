<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Lang extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:lang');
        // 设置参数
        $this->setDescription('这个指令用来创建语言包 例：php think make:lang zh-cn');
        $this->setHelp('这个指令用来创建语言包 例：php think make:lang zh-cn');
        $this->addArgument('lang', null, "language name");
    }

    protected function execute(Input $input, Output $output)
    {
        $lang = $input->getArgument('lang');
        $this->replaceAndSave($lang);
    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'lang.stub';
        return file_get_contents($stubPath);
    }

    protected function replaceAndSave($lang='zh-cn')
    {
        $appPath = env('APP_PATH');
        $contents = $this->read();
        $ds = DIRECTORY_SEPARATOR;
        $langPath = "{$appPath}lang{$ds}";

        if (!is_dir($langPath)) {
            mkdir($langPath, 0777, true);
        }

        if (!file_exists($file = "{$langPath}{$lang}.php")) {
            file_put_contents($file, $contents);
            echo "创建语言包:{$lang}成功","\n";
        }else{
            echo "语言包{$lang}已存在","\n";
        }
    }

}
