<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class MakeApiModel extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:api-model');
        // 设置参数
        $this->setDescription('这个指令用来创建API模型 例：php think make:api-model Customer');
        $this->setHelp('这个指令用来创建API模型 例：php think make:api-model Customer');
        $this->addArgument('model', null, "modelName");
    }

    protected function execute(Input $input, Output $output)
    {
        $model_name = $input->getArgument('model');
        $this->replaceAndSave($model_name);
    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'model.stub';
        return file_get_contents($stubPath);
    }

    protected function replaceAndSave($model_name)
    {
        $appPath = env('APP_PATH');
        $contents = $this->read();
        $ds = DIRECTORY_SEPARATOR;
        $contents = str_replace('{%ModelName%}', $model_name, $contents);
        $savePath = "{$appPath}common{$ds}model{$ds}";

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        if (!file_exists($file = $savePath . "{$model_name}.php")) {
            file_put_contents($file, $contents);
            echo "创建API模型:{$model_name}成功","\n";
        }else{
            echo "{$model_name}已存在","\n";
        }
    }
}
