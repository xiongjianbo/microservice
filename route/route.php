<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::group('', function () {
    Route::post('login', 'Auth/Auth/login')->middleware('Validate')->allowCrossDomain();
    Route::get('logout', 'Auth/Auth/logout')->middleware('Auth')->allowCrossDomain();
    Route::get('doc', 'Index/Index/index');

    Route::resource('customer', 'business/CustomerController');
    Route::get('customer-info-trashed/:id', 'business/CustomerController/customerInfoTrashed');
    Route::resource('customer-info', 'business/CustomerInfoController');

    /*项目类别*/
    Route::resource('project-type', 'project/ProjectTypeController');
    /*任务类别*/
    Route::resource('task-type', 'project/TaskTypeController');
    /*获取项目类别树*/
    Route::get('project-type-tree', 'project/ProjectTypeController/tree');
    /*项目类别*/
    Route::resource('currency', 'account/CurrencyController');
    /*获取货币的键值对*/
    Route::get('currency-pair', 'account/CurrencyController/pair');
    /*项目类别*/
    Route::resource('skill-cate', 'project/SkillCategoryController');
    /*获取货币的键值对*/
    Route::get('skill-cate-pair', 'project/SkillCategoryController/pair');


    /*项目类别*/
    Route::resource('project-type', 'project/ProjectTypeController');
    /*获取项目类别树*/
    Route::get('project-type-tree', 'project/ProjectTypeController/tree');
    /*项目类别*/
    Route::resource('currency', 'account/CurrencyController');
    /*获取货币的键值对*/
    Route::get('currency-pair', 'account/CurrencyController/pair');
    /*项目类别*/
    Route::resource('skill-cate', 'project/SkillCategoryController');
    /*获取货币的键值对*/
    Route::get('skill-cate-pair', 'project/SkillCategoryController/pair');

    /**zjg**/
    //薪酬设置相关接口
    Route::resource('salary-plan', 'setting/SalaryPlanController');       //薪酬方案
    Route::get('salary-get', 'setting/SalaryPlanController/getInfo');  //自动获取设置
    Route::resource('salary', 'setting/SalaryController');                //薪酬底薪
    Route::delete('salary-delete', 'setting/salaryController/delete');     //清空底薪
    Route::resource('salary-level', 'setting/SalaryLevelController');     //薪酬等级设置
    Route::resource('salary-merits', 'setting/SalaryMeritsController');    //薪酬奖金设置
    Route::resource('salary-insurance', 'setting/SalaryInsuranceController');    //薪酬社保设置
    Route::resource('salary-deduction', 'setting/SalaryDeductionController');    //薪酬扣款设置
    Route::resource('salary-tax', 'setting/SalaryTaxController');    //薪酬个税设置

    //预置设置
    Route::resource('preset-program', 'setting/PresetProgramController');   //程序业务预置
    Route::get('preset-program-tree', 'setting/PresetProgramController/tree');   //程序业务获取树形结构

    //订单
    Route::resource('orders', 'business/OrdersController');
    Route::get('list-order','business/OrdersController/lists');
    //项目
    Route::resource('projects', 'project/ProjectsController');
    Route::get('list-project','project/ProjectsController/lists');
    Route::resource('project-delivery', 'project/ProjectDeliveryController');
    Route::post('project-delivery/audit','project/ProjectDeliveryController/audit');

    /**end-zjg**/

    //语言设置
    Route::resource('language', 'language/LanguageController');

    /*任务*/
    Route::resource('task', 'project/TaskController');

    /*任务批量新建*/
    Route::put('task-batch', 'project/TaskController/taskBatch');

    /*任务提交申请*/
    Route::resource('task-submit', 'project/TaskSubmitController');

    /*终止任务*/
    Route::put('task-stop/:id', 'project/TaskController/stopTask');

    /*更新任务进度*/
    Route::put('task-progress/:task_id', 'project/TaskController/updateTaskProgress');

    /*拒绝任务*/
    Route::put('task-refuse/:task_id', 'project/TaskController/refuseTask');

    /*接受任务*/
    Route::put('task-accept/:task_id', 'project/TaskController/acceptTask');

    //es测试
    Route::get('es/test','es/EsController/test');
    Route::get('es/test2','es/EsController/test2');
    Route::get('es/test3','es/EsController/test3');

})->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With,token')
    ->allowCrossDomain();


Route::miss(function () {
    return jsonResponse([], '欢迎使用项目管理系统', 200);
});
