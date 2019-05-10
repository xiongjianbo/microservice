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
    // 考勤系统
    Route::group('attendance', function () {
        Route::get('/', 'index');
        Route::get('/:number$', 'show');
        Route::get('/:number/showInfo', 'showInfo');
        Route::put('/:id', 'update');
        Route::post('/', 'import');
    })->middleware(['Validate', 'Check'])->prefix('personnel/Attendance/');
    // 配置管理
    Route::group('configure', function () {
        Route::get('/', 'index')->middleware('Check');
        Route::get('/:name', 'show');
        Route::put('/:id', 'update');
    })->prefix('configure/Config/');

    // 部门管理
    Route::group('department', function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('Validate');
        Route::put('/:id', 'update')->middleware('Validate');
        Route::delete('/:id', 'destroy')->middleware('Validate');
    })->middleware('Check')->prefix('personnel/Department/');


    // 职位管理
    Route::group('position', function () {
        Route::get('/rule', 'rule');
        Route::post('/', 'store')->middleware('Check');
        Route::get('/:id', 'show')->middleware('Check');
        Route::put('/:id', 'update')->middleware('Check');
        Route::delete('/:id', 'destroy')->middleware('Check');
    })->middleware(['Validate'])->prefix('personnel/Position/');

    // 员工档案管理
    Route::group('personnel', function () {
        Route::get('/', 'index')->middleware('Check');
        Route::post('/', 'store')->middleware('Check');
        Route::get('/:id', 'show');
        Route::put('/:id', 'update')->middleware('Check');
        Route::delete('/:id', 'destroy')->middleware('Check');
        Route::post('/updateSelf', 'updateSelf');
    })->middleware('Validate')->prefix('personnel/Personnel/');

    // 培训管理
    Route::group('train', function () {
        Route::get('/', 'TrainClass/index');                    // 培训课程列表
        Route::post('/', 'TrainClass/store');                   // 新增一个培训课程
        Route::get('/:id$', 'TrainClass/show');                 // 查看培训课程相信信息
        Route::put('/:id$', 'TrainClass/update');               // 更新一个培训课程信息
        Route::delete('/:id$', 'TrainClass/destroy');           // 删除一个培训课程信息

        Route::get('/personnel/:id$', 'TrainPersonnel/index');  // 查看报名列表
        Route::put('/personnel/:id$', 'TrainPersonnel/join');   // 用户报名和取消报名
        Route::put('/personnel/score/:id', 'TrainPersonnel/score');                     // 编辑员工考试结果
        Route::get('/personnel/comment-list/:id', 'TrainPersonnel/commentList');        // 获取课程的评论列表
        Route::get('/personnel/comment-census/:id', 'TrainPersonnel/commentCensus');    // 获取课程的评论占比情况
        Route::put('/personnel/comment-edit/:id', 'TrainPersonnel/commentEdit');        // 用户提交评论打分情况
        Route::put('/personnel/comment-add/:id', 'TrainPersonnel/commentAdd');          // 用户新增一条评论

        Route::get('resource/:id', 'TrainResource/index');      // 获取一个培训课程的资源信息(培训记录)
        Route::put('resource/:id', 'TrainResource/update');     // 更新一个培训课程的资源信息(培训记录)
    })->middleware(['Validate', 'Check'])->prefix('personnel/');

    // 招聘管理
    Route::group('recruit', function () {
        Route::get('/inner-index', 'innerIndex');                   // 查看内推列表
        Route::post('/inner-store', 'innerStore');                  // 新增内推
        Route::put('/inner-update/:id', 'innerUpdateStatus');       // 修改内推状态
        Route::delete('/inner-destroy/:id', 'innerDestroy');        // 删除一个内推
    })->middleware(['Validate', 'Check'])->prefix('personnel/Recruit/');

    // 系统公告
    Route::group('notice', function () {
        Route::get('/$', 'index');                  // 查看公告列表
        Route::get('/:id$', 'show');                // 查看公告详情
        Route::post('/', 'store');                  // 新增公告
        Route::delete('/:id$', 'destroy');          // 删除一个公告
        Route::put('/read/:id$', 'read');           // 设置公告为已阅读
        Route::get('/read/:id$', 'readDetail');     // 设置公告为已阅读
    })->middleware(['Validate', 'Check'])->prefix('personnel/Notice/');

    // 审批流程
    Route::group('process', function () {
        Route::get('/type/:id$', 'index');          // 获取审批规则流程表
        Route::post('/', 'store');                  // 新增一个审批规则
        Route::put('/:id$', 'update');              // 编辑一个审批规则
        Route::delete('/:id$', 'destroy');          // 删除一个审批规则
    })->middleware(['Validate', 'Check'])->prefix('personnel/ProcessRule/');

    // 请假相关的接口
    Route::group('leave', function () {
        Route::post('/', 'store');                  // 新增一个请假申请
        Route::get('/', 'index');                   // 获取请假列表
        Route::get('/show/:id$', 'show');           // 获取请假详情
        Route::get('/wait', 'wait');                // 筛选待审批的请假列表
        Route::put('/:id$', 'update');              // 请假审批,通过、不通过
    })->middleware(['Validate', 'Check'])->prefix('personnel/SupplementLeave/');

    // 补卡相关的接口
    Route::group('sign', function () {
        Route::post('/', 'store');                  // 提交补卡申请
        Route::get('/', 'index');                   // 获取补卡列表
        Route::get('/show/:id$', 'show');           // 获取补卡详情
        Route::get('/wait', 'wait');                // 筛选待审批的补卡列表
        Route::put('/:id$', 'update');              // 补卡审批,通过、不通过
    })->middleware(['Validate', 'Check'])->prefix('personnel/SupplementSign/');

    // 供应商管理
    Route::group('supplier', function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('Check');
        Route::get('/:id', 'show');
        Route::put('/:id', 'update')->middleware('Check');
    })->middleware('Validate')->prefix('asset/Supplier/');

    // 采购相关的接口
    Route::group('purchase', function () {
        Route::post('/', 'store');                  // 提交采购申请
        Route::get('/', 'index');                   // 获取采购列表
        Route::get('/show/:id$', 'show');           // 获取采购详情
        Route::get('/wait', 'wait');                // 筛选待审批的采购列表
        Route::put('/:id$', 'update');              // 采购审批,通过、不通过
    })->middleware(['Validate', 'Check'])->prefix('asset/SupplementPurchase/');

    //个人设置
    Route::put('personal-settings', 'personnel/PersonalSettingManage/set');

    //反馈统计的接口
    Route::group('feedback', function () {
        Route::get('/', 'index');                       //获取反馈信息列表
    })->prefix('project/FeedBackController/');

    Route::group('feedbackInfo', function () {
        Route::get('/', 'index');                       //获取反馈详情信息
    })->prefix('project/FeedbackInfoController/');
})->middleware('Auth')
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With,token')
    ->allowCrossDomain();