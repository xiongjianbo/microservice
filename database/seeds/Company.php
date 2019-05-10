<?php

use think\migration\Seeder;

class Company extends Seeder
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
        $rules = [
            "personnel-Department-index",
            "personnel-Department-store",
            "personnel-Department-update",
            "personnel-Department-destroy",
            "personnel-Position-store",
            "personnel-Position-show",
            "personnel-Position-update",
            "personnel-Position-destroy",
            "personnel-Personnel-index",
            "personnel-Personnel-store",
            "personnel-Personnel-show",
            "personnel-Personnel-update",
            "personnel-Personnel-destroy",
            "personnel-TrainClass-index",
            "personnel-TrainClass-store",
            "personnel-TrainClass-show",
            "personnel-TrainClass-update",
            "personnel-TrainClass-destroy",
            "personnel-TrainPersonnel-index",
            "personnel-TrainPersonnel-join",
            "personnel-TrainPersonnel-score",
            "personnel-TrainPersonnel-commentlist",
            "personnel-TrainPersonnel-commentcensus",
            "personnel-TrainPersonnel-commentedit",
            "personnel-TrainPersonnel-commentadd",
            "personnel-TrainResource-index",
            "personnel-TrainResource-update",
            "personnel-Recruit-innerindex",
            "personnel-Recruit-innerstore",
            "personnel-Recruit-innerupdatestatus",
            "personnel-Recruit-innerdestroy",
            "personnel-Notice-index",
            "personnel-Notice-show",
            "personnel-Notice-store",
            "personnel-Notice-destroy",
            "personnel-Notice-read",
            "personnel-Notice-readdetail",
            "personnel-ProcessRule-store",
            "personnel-ProcessRule-destroy",
            "personnel-ProcessRule-index",
            "personnel-ProcessRule-update",
            "personnel-SupplementLeave-store",
            "personnel-SupplementLeave-index",
            "personnel-SupplementLeave-show",
            "personnel-SupplementLeave-wait",
            "personnel-SupplementLeave-update",
            "personnel-SupplementLeave-update",
            "personnel-Attendance-index",
            "personnel-Attendance-show",
            "personnel-Attendance-showInfo",
            "personnel-Attendance-update",
            "personnel-Attendance-import",
        ];

        $data = [
            [
                'name' => '成都动画谷数码科技有限公司',
                'rules' => json_encode($rules),
                'keyword' => 'CF3BF7EE0FC90DFAB8FF920F71746E0C',
            ]
        ];

        $posts = $this->table('company');
        $posts->insert($data)
            ->save();

    }
}
