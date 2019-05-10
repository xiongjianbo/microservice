<?php

use think\migration\Seeder;

class Rule extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            /**
             *【level】级别:1-模块,2-控制器,3-操作
             *【scope】权限范围：1-全部；2-部门；3-个人；
             * 方法名全小写
             */
            ['id' => 10000, 'p_id' => 0, 'title' => '人事管理', 'name' => 'personnel', 'level' => 1],

            ['id' => 10100, 'p_id' => 10000, 'title' => '部门管理', 'name' => 'personnel-department', 'level' => 2],
            ['id' => 10101, 'p_id' => 10100, 'title' => '获取部门列表', 'name' => 'personnel-department-index', 'level' => 3],
            ['id' => 10102, 'p_id' => 10100, 'title' => '新增部门', 'name' => 'personnel-department-store', 'level' => 3],
            ['id' => 10103, 'p_id' => 10100, 'title' => '更新部门', 'name' => 'personnel-department-update', 'level' => 3],
            ['id' => 10104, 'p_id' => 10100, 'title' => '删除部门', 'name' => 'personnel-department-destroy', 'level' => 3],

            ['id' => 10200, 'p_id' => 10000, 'title' => '职位管理', 'name' => 'personnel-position', 'level' => 2],
            ['id' => 10201, 'p_id' => 10200, 'title' => '新增职位', 'name' => 'personnel-position-store', 'level' => 3],
            ['id' => 10202, 'p_id' => 10200, 'title' => '获取职位', 'name' => 'personnel-position-show', 'level' => 3],
            ['id' => 10203, 'p_id' => 10200, 'title' => '更新职位', 'name' => 'personnel-position-update', 'level' => 3],
            ['id' => 10204, 'p_id' => 10200, 'title' => '删除职位', 'name' => 'personnel-position-destroy', 'level' => 3],

            ['id' => 10300, 'p_id' => 10000, 'title' => '员工管理', 'name' => 'personnel-personnel', 'level' => 2],
            ['id' => 10301, 'p_id' => 10300, 'title' => '获取员工列表', 'name' => 'personnel-personnel-index', 'level' => 3, 'scope' => '1,2'],
            ['id' => 10302, 'p_id' => 10300, 'title' => '新增员工', 'name' => 'personnel-personnel-store', 'level' => 3],
            ['id' => 10303, 'p_id' => 10300, 'title' => '获取员工详情', 'name' => 'personnel-personnel-show', 'level' => 3],
            ['id' => 10304, 'p_id' => 10300, 'title' => '更新员工信息', 'name' => 'personnel-personnel-update', 'level' => 3],
            ['id' => 10305, 'p_id' => 10300, 'title' => '员工离职', 'name' => 'personnel-personnel-destroy', 'level' => 3],

            ['id' => 10400, 'p_id' => 10000, 'title' => '培训管理', 'name' => 'personnel-TrainClass', 'level' => 2],
            ['id' => 10401, 'p_id' => 10400, 'title' => '获取培训列表', 'name' => 'personnel-TrainClass-index', 'level' => 3, 'scope' => '1,2,3'],
            ['id' => 10402, 'p_id' => 10400, 'title' => '新增培训', 'name' => 'personnel-TrainClass-store', 'level' => 3],
            ['id' => 10403, 'p_id' => 10400, 'title' => '获取培训详情', 'name' => 'personnel-TrainClass-show', 'level' => 3],
            ['id' => 10404, 'p_id' => 10400, 'title' => '更新培训详细信息', 'name' => 'personnel-TrainClass-update', 'level' => 3],
            ['id' => 10405, 'p_id' => 10400, 'title' => '删除培训', 'name' => 'personnel-TrainClass-destroy', 'level' => 3],

            ['id' => 10500, 'p_id' => 10000, 'title' => '培训记录(资源)', 'name' => 'personnel-TrainResource', 'level' => 2],
            ['id' => 10501, 'p_id' => 10500, 'title' => '获取培训记录(资源)', 'name' => 'personnel-TrainResource-index', 'level' => 3],
            ['id' => 10502, 'p_id' => 10500, 'title' => '编辑培训记录(资源)', 'name' => 'personnel-TrainResource-update', 'level' => 3],

            ['id' => 10600, 'p_id' => 10000, 'title' => '培训参与管理', 'name' => 'personnel-TrainPersonnel', 'level' => 2],
            ['id' => 10601, 'p_id' => 10600, 'title' => '查看报名列表(考试结果)', 'name' => 'personnel-TrainPersonnel-index', 'level' => 3],
            ['id' => 10602, 'p_id' => 10600, 'title' => '报名、取消报名', 'name' => 'personnel-TrainPersonnel-join', 'level' => 3],
            ['id' => 10603, 'p_id' => 10600, 'title' => '编辑考试结果', 'name' => 'personnel-TrainPersonnel-score', 'level' => 3],
            ['id' => 10604, 'p_id' => 10600, 'title' => '提交考试打分', 'name' => 'personnel-TrainPersonnel-commentedit', 'level' => 3],
            ['id' => 10605, 'p_id' => 10600, 'title' => '提交培训评论', 'name' => 'personnel-TrainPersonnel-commentadd', 'level' => 3],
            ['id' => 10606, 'p_id' => 10600, 'title' => '获取评分统计信息', 'name' => 'personnel-TrainPersonnel-commentcensus', 'level' => 3],
            ['id' => 10607, 'p_id' => 10600, 'title' => '获取评论列表', 'name' => 'personnel-TrainPersonnel-commentlist', 'level' => 3],

            ['id' => 10700, 'p_id' => 10000, 'title' => '招聘管理', 'name' => 'personnel-Recruit', 'level' => 2],
            ['id' => 10701, 'p_id' => 10700, 'title' => '获取内推列表', 'name' => 'personnel-Recruit-innerindex', 'level' => 3],
            ['id' => 10702, 'p_id' => 10700, 'title' => '新增内推', 'name' => 'personnel-Recruit-innerstore', 'level' => 3],
            ['id' => 10703, 'p_id' => 10700, 'title' => '修改内推状态', 'name' => 'personnel-Recruit-innerupdatestatus', 'level' => 3],
            ['id' => 10704, 'p_id' => 10700, 'title' => '删除内推', 'name' => 'personnel-Recruit-innerdestroy', 'level' => 3],

            ['id' => 10800, 'p_id' => 10000, 'title' => '系统公告', 'name' => 'personnel-Notice', 'level' => 2],
            ['id' => 10801, 'p_id' => 10800, 'title' => '查看公告列表', 'name' => 'personnel-Notice-index', 'level' => 3, 'scope' => '1,2,3'],
            ['id' => 10802, 'p_id' => 10800, 'title' => '查看公告详情', 'name' => 'personnel-Notice-show', 'level' => 3],
            ['id' => 10803, 'p_id' => 10800, 'title' => '新增公告', 'name' => 'personnel-Notice-store', 'level' => 3],
            ['id' => 10804, 'p_id' => 10800, 'title' => '删除公告', 'name' => 'personnel-Notice-destroy', 'level' => 3],
            ['id' => 10805, 'p_id' => 10800, 'title' => '设置公告为已读', 'name' => 'personnel-Notice-read', 'level' => 3],
            ['id' => 10806, 'p_id' => 10800, 'title' => '获取已读和未读详情', 'name' => 'personnel-Notice-readdetail', 'level' => 3],

            ['id' => 10900, 'p_id' => 10000, 'title' => '流程管理', 'name' => 'personnel-ProcessRule', 'level' => 2],
            ['id' => 10901, 'p_id' => 10900, 'title' => '新增一个流程', 'name' => 'personnel-ProcessRule-store', 'level' => 3],
            ['id' => 10902, 'p_id' => 10900, 'title' => '删除一个流程', 'name' => 'personnel-ProcessRule-destroy', 'level' => 3],
            ['id' => 10903, 'p_id' => 10900, 'title' => '查询流程详情', 'name' => 'personnel-ProcessRule-index', 'level' => 3],
            ['id' => 10904, 'p_id' => 10900, 'title' => '修改一个流程', 'name' => 'personnel-ProcessRule-update', 'level' => 3],

            ['id' => 11000, 'p_id' => 10000, 'title' => '请假管理', 'name' => 'personnel-SupplementLeave', 'level' => 2],
            ['id' => 11001, 'p_id' => 11000, 'title' => '新增一个请假申请', 'name' => 'personnel-SupplementLeave-store', 'level' => 3],
            ['id' => 11002, 'p_id' => 11000, 'title' => '获取请假列表', 'name' => 'personnel-SupplementLeave-index', 'level' => 3, 'scope' => '1,2,3'],
            ['id' => 11003, 'p_id' => 11000, 'title' => '获取请假详情', 'name' => 'personnel-SupplementLeave-show', 'level' => 3],
            ['id' => 11004, 'p_id' => 11000, 'title' => '筛选请假审批列表', 'name' => 'personnel-SupplementLeave-wait', 'level' => 3],
            ['id' => 11005, 'p_id' => 11000, 'title' => '请假审批(通过、不通过)', 'name' => 'personnel-SupplementLeave-update', 'level' => 3],

            ['id' => 11100, 'p_id' => 10000, 'title' => '补卡管理', 'name' => 'personnel-SupplementSign', 'level' => 2],
            ['id' => 11101, 'p_id' => 11100, 'title' => '新增一个补卡申请', 'name' => 'personnel-SupplementSign-store', 'level' => 3],
            ['id' => 11102, 'p_id' => 11100, 'title' => '获取补卡列表', 'name' => 'personnel-SupplementSign-index', 'level' => 3, 'scope' => '1,2,3'],
            ['id' => 11103, 'p_id' => 11100, 'title' => '获取补卡详情', 'name' => 'personnel-SupplementSign-show', 'level' => 3],
            ['id' => 11104, 'p_id' => 11100, 'title' => '筛选补卡审批列表', 'name' => 'personnel-SupplementSign-wait', 'level' => 3],
            ['id' => 11105, 'p_id' => 11100, 'title' => '补卡审批(通过、不通过)', 'name' => 'personnel-SupplementSign-update', 'level' => 3],

            ['id' => 11200, 'p_id' => 10000, 'title' => '考勤管理', 'name' => 'personnel-Attendance', 'level' => 2],
            ['id' => 11201, 'p_id' => 11200, 'title' => '获取考勤列表', 'name' => 'personnel-Attendance-index', 'level' => 3, 'scope' => '1,2,3'],
            ['id' => 11202, 'p_id' => 11200, 'title' => '获取个人考勤月记录', 'name' => 'personnel-Attendance-show', 'level' => 3],
            ['id' => 11203, 'p_id' => 11200, 'title' => '获取个人考勤日记录', 'name' => 'personnel-Attendance-showInfo', 'level' => 3],
            ['id' => 11204, 'p_id' => 11200, 'title' => '导入考勤数据', 'name' => 'personnel-Attendance-import', 'level' => 3],
            ['id' => 11205, 'p_id' => 11200, 'title' => '修改单条记录', 'name' => 'personnel-Attendance-update', 'level' => 3],

            ['id' => 11301, 'p_id' => 11300, 'title' => '个人设置', 'name' => 'personnel-PersonalSettingManage-set', 'level' => 3, 'scope' => '1,2,3'],
        ];

        $posts = $this->table('rule');
        $posts->insert($data)
            ->save();

    }
}
