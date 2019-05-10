<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class MakeFacade extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:facade');
        // 设置参数
        $this->setDescription('这个指令用来创建门面 例：php think make:facade Excel=\\\\excel\\\\Excel');
        $this->setHelp('这个指令用来创建门面.例：php think make:facade Excel=\\\\excel\\\\Excel');
        $this->addArgument('facade', null, "facadeName");
    }

    protected function execute(Input $input, Output $output)
    {
        $facadeName = $input->getArgument('facade');
        $param = explode('=', $facadeName);

        //一种防止list出错的优雅写法
        !isset($param[1]) && $param[] = '';
        list($className, $classPath) = $param;
        $this->replaceAndSave($className, $classPath);
    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'facade.stub';
        return file_get_contents($stubPath);
    }

    protected function replaceAndSave($className, $classPath)
    {
        $appPath = env('APP_PATH');
        $contents = $this->read();
        $contents = str_replace('{%className%}', $className, $contents);
        $contents = str_replace('{%classPath%}', $classPath, $contents);
        $savePath = "{$appPath}facade" . DIRECTORY_SEPARATOR;

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        if (!file_exists($file = $savePath . "{$className}.php")) {
           file_put_contents($file, $contents);
            echo "创建Facade:{$className}成功","\n";
        }else{
            echo "{$className}已存在","\n";
        }
    }
}
