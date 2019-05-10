<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class MakeDoc extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:doc');
        // 设置参数
        $this->setDescription('这个指令用来创建文档 例：php think make:doc（未完成）');
        $this->setHelp('这个指令用来创建门面.例：php think make:doc（未完成）');
        // 创建word, excel, pdf文档，下个版本实现
//        $this->addArgument('doc', null, "docName");
    }

    protected function execute(Input $input, Output $output)
    {
        // 1.判断doc-route是否存在
        $route_path = env('ROUTE_PATH');
        $doc_route_path = "{$route_path}doc-route.php";

        // 2.如果不存在则创建 存在则放过
        if (!file_exists($doc_route_path)) {

        }
        print_r($doc_route_path);
    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'doc-route.stub';
        return file_get_contents($stubPath);
    }


}
