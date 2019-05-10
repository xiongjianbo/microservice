<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;
use think\Console;
use think\facade\Config;
use think\facade\App;
use think\facade\Env;

class MakeApi extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:api');
        // 设置参数
        $this->setDescription('这个指令用来创建Api 控制器+模型+验证 例：php think make:api business/CustomerController -m=Customer');
        $this->setHelp('这个指令用来创建Api 控制器+模型+验证 例：php think make:api business/CustomerController -m=Customer');
        $this->addArgument('api', null, "ApiName");
        $this->addOption('model_name', 'm', Option::VALUE_REQUIRED, 'ModelName');
    }

    protected function execute(Input $input, Output $output)
    {
        $api = $input->getArgument('api');
        $model = $input->getOption('model_name');
        if ($model[0] == '=') {
            $model = substr($model, 1);
        }

        if ($api == $model) {
            $output->writeln("控制器名不能和模型名称一样，如果你非要这么做，请给模型加上别名");
            return false;
        }
        $api = trim($api);
        $model = trim($model);

        $this->makeApiController($api, $model);
        $this->makeModel($model);
        $this->makeValidate($model);
    }

    protected function makeApiController($api, $model)
    {
        $className = $this->getFullClassName($api);
        $pathName = $this->getPathName($className);
        $this->replaceAndSave($className, $pathName, $model);
    }

    protected function makeModel($model)
    {
        $appPath = env('APP_PATH');
        $ds = DIRECTORY_SEPARATOR;
        $savePath = "{$appPath}common{$ds}model{$ds}";
        if (!file_exists("{$savePath}{$model}.php")) {
            Console::call("make:api-model", [
                'model' => $model
            ]);
        }
    }

    protected function makeValidate($validator)
    {
        $appPath = env('APP_PATH');
        $ds = DIRECTORY_SEPARATOR;
        $savePath = "{$appPath}common{$ds}validate{$ds}";
        if (!file_exists("{$savePath}{$validator}.php")) {
            Console::call("make:api-validate", [
                'validate' => $validator
            ]);
        }
    }

    protected function read()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'api.stub';
        return file_get_contents($stubPath);
    }

    protected function replaceAndSave($className, $pathName, $modelName)
    {
        $controllerName = $this->getController($className);
        $nameSpace = substr($className, 0, -strlen($controllerName) - 1);
        $contents = $this->read();
        $contents = str_replace('{%namespace%}', $nameSpace, $contents);
        $contents = str_replace('{%modelName%}', $modelName, $contents);
        $contents = str_replace('{%ControllerName%}', $controllerName, $contents);

        if (!is_dir($dir = dirname($pathName))) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($pathName)) {
            file_put_contents($pathName, $contents);
            echo "创建Api:{$controllerName}成功", "\n";
        } else {
            echo "{$controllerName}已存在", "\n";
        }
    }

    protected function getController($namespaceName = '', $tag = "\\")
    {
        $nameArr = explode($tag, $namespaceName);
        return current(array_reverse($nameArr));
    }

    protected function getPathName($name)
    {
        $name = str_replace(App::getNamespace() . '\\', '', $name);
        $ds = DIRECTORY_SEPARATOR;
        $file = Env::get('app_path') . ltrim(str_replace('\\', '/', $name), '/') . '.php';
        $controller = $this->getController($file, $ds);
        $dir = dirname($file);
        return "{$dir}{$ds}controller{$ds}{$controller}";
    }

    protected function getClassName($name)
    {
        $appNamespace = App::getNamespace();

        if (strpos($name, $appNamespace . '\\') !== false) {
            return $name;
        }

        if (Config::get('app_multi_module')) {
            if (strpos($name, '/')) {
                list($module, $name) = explode('/', $name, 2);
            } else {
                $module = 'common';
            }
        } else {
            $module = null;
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($appNamespace, $module) . '\\' . $name;
    }

    protected function getNamespace($appNamespace, $module)
    {
        return $module ? ($appNamespace . '\\' . $module) : $appNamespace;
    }

    protected function getFullClassName($name)
    {
        return $this->getClassName($name) . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '');
    }

    protected function getFullNamespace($appNamespace, $module)
    {
        return $this->getNamespace($appNamespace, $module) . '\controller';
    }

}
