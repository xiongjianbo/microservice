![avatar](favicon.png)

## CGLand
> 运行环境要求

   + 运行环境要求PHP7.3+、mysql 8.0.4、redis 3.2+、nginx 1.10+
   + web目录下的代码可自行搭建LNMP开发环境
   + 推荐使用[docker](https://download.docker.com/win/stable/Docker%20for%20Windows%20Installer.exe)快速搭建
   + 不要升级到thinkphp 6.0，不要修改composer.json，框架底层不向下兼容，会产生不可预料的问题。

### 快速搭建localhost环境：
    docker-compose up

### docker常用命令
+ docker-compose up 运行容器
+ docker-compose down 停止容器
+ docker exec -ti pmc_php_1 sh 进入pmc容器

### 数据库

>创建数据库

    php think make:db pmc

>删除数据库

    php think rm:db pmc

>创建迁移文件

    php think migrate:create Users

>回滚最近一次迁移

    php think migrate:rollback

>回滚所有迁移文件

    php think migrate:rollback -t 0

>从当前配置正确的数据库创建迁移文件

    php think migrate:from-mysql

>运行迁移文件

    php think migrate:run

>创建数据填充

    php think seed:create PersonnelFile 

>执行数据填充

    php think seed:run
    php think seed:run -s Users

>数据重新填充（清空数据表再次填充）

    php think seed:reset

### 控制器

>新建资源控制器

     php think make:controller auth/AuthRule 

>新建API控制器(常用，重要!)

     php think make:controller --api auth/AuthRule 

>新建控制器

     php think make:controller auth/AuthRule --plain

>新建 验证器类

     php think make:validate  index/User

### 模型

>新建模型(模型尽量放在common模块)

     php think make:model common/School

### 中间件

>新建中间件

     php think make:middleware Auth  

### 门面

>新建门面

     php think make:facade Excel=\\excel\\Excel

>删除门面

     php think rm:facade Excel

### 缓存
>生成路由映射缓存

    php think optimize:route

>默认生成应用的配置缓存文件，调用后会在runtime目录下面生成init.php文件，生成配置缓存文件后，应用目录下面的config.phpcommon.php以及tags.php不会被加载，被runtime/init.php取代。

    php think optimize:config  

>生成类库映射文件

    php think optimize:autoload 

### 清除缓存

>不带任何参数调用clear命令的话，会清除runtime目录下文件

     php think clear  

>清除数据缓存目录

     php think clear --cache 

>清除路由缓存

     php think clear --route

>清除日志目录

     php think clear --log   

### 快速生成模块

>可以用来自动生成需要的模块及目录结构和文件等

     php think build --module test

### 查看thinkphp版本

    php think version

### 国际化相关

    语言包位于
    application/lang/en.php
    调用语言包
    lang('Undefined variable');
    
###  变量/类/函数命名
    
[codelf](https://unbug.github.io/codelf/)

    https://unbug.github.io/codelf/