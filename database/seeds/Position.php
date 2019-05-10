<?php

use think\migration\Seeder;

class Position extends Seeder
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
            [
                'id' => 1,
                'title' => '部门经理',
                'status' => 1,
                'rules' => json_encode([
                    "personnel-Personnel-index" => 2,
                    "personnel-ProcessRule-store" => 1,
                    "personnel-ProcessRule-destroy" => 1,
                    "personnel-ProcessRule-index" => 1,
                    "personnel-ProcessRule-update" => 1,
                    "personnel-SupplementLeave-store" => 1,
                    "personnel-SupplementLeave-index" => 2,
                    "personnel-SupplementLeave-show" => 1,
                    "personnel-SupplementLeave-wait" => 1,
                    "personnel-SupplementLeave-update" => 1,
                    "personnel-SupplementSign-store" => 1,
                    "personnel-SupplementSign-index" => 2,
                    "personnel-SupplementSign-show" => 1,
                    "personnel-SupplementSign-wait" => 1,
                    "personnel-SupplementSign-update" => 1,
                    "personnel-SupplementPurchase-store" => 1,
                    "personnel-SupplementPurchase-index" => 2,
                    "personnel-SupplementPurchase-show" => 1,
                    "personnel-SupplementPurchase-wait" => 1,
                    "personnel-SupplementPurchase-update" => 1,
                    "personnel-TrainClass-index" => 1,
                    "personnel-TrainClass-store" => 1,
                    "personnel-TrainClass-show" => 1,
                    "personnel-TrainClass-update" => 1,
                    "personnel-TrainClass-destroy" => 1,
                    "personnel-TrainPersonnel-index" => 1,
                    "personnel-TrainPersonnel-join" => 1,
                    "personnel-TrainPersonnel-score" => 1,
                    "personnel-TrainPersonnel-commentlist" => 1,
                    "personnel-TrainPersonnel-commentcensus" => 1,
                    "personnel-TrainPersonnel-commentedit" => 1,
                    "personnel-TrainPersonnel-commentadd" => 1,
                    "personnel-TrainResource-index" => 1,
                    "personnel-TrainResource-update" => 1,
                ]),
                'department_id' => 6,
                'company_id' => 1
            ],
            [
                'id' => 2,
                'title' => '员工',
                'status' => 1,
                'rules' => json_encode([
                    "personnel-ProcessRule-store" => 1,
                    "personnel-ProcessRule-destroy" => 1,
                    "personnel-ProcessRule-index" => 1,
                    "personnel-ProcessRule-update" => 1,
                    "personnel-SupplementLeave-store" => 1,
                    "personnel-SupplementLeave-index" => 2,
                    "personnel-SupplementLeave-show" => 1,
                    "personnel-SupplementSign-store" => 1,
                    "personnel-SupplementSign-index" => 2,
                    "personnel-SupplementSign-show" => 1,
                    "personnel-SupplementPurchase-store" => 1,
                    "personnel-SupplementPurchase-index" => 2,
                    "personnel-SupplementPurchase-show" => 1,
                    "personnel-TrainClass-index" => 1,
                    "personnel-TrainClass-store" => 1,
                    "personnel-TrainClass-show" => 1,
                    "personnel-TrainClass-update" => 1,
                    "personnel-TrainClass-destroy" => 1,
                    "personnel-TrainPersonnel-index" => 1,
                    "personnel-TrainPersonnel-join" => 1,
                    "personnel-TrainPersonnel-score" => 1,
                    "personnel-TrainPersonnel-commentlist" => 1,
                    "personnel-TrainPersonnel-commentcensus" => 1,
                    "personnel-TrainPersonnel-commentedit" => 1,
                    "personnel-TrainPersonnel-commentadd" => 1,
                    "personnel-TrainResource-index" => 1,
                    "personnel-TrainResource-update" => 1,
                ]),
                'department_id' => 8,
                'company_id' => 1
            ],
            [
                'id' => 3,
                'title' => '人事',
                'status' => 1,
                'rules' => json_encode([
                    "personnel-Personnel-index" => 1,
                    "personnel-Personnel-store" => 1,
                    "personnel-Position-store" => 1,
                    "personnel-Position-show" => 1,
                    "personnel-Position-update" => 1,
                    "personnel-Position-destroy" => 1,
                    "personnel-Department-index" => 1,
                    "personnel-Department-store" => 1,
                    "personnel-Department-update" => 1,
                    "personnel-Department-destroy" => 1,
                    "personnel-ProcessRule-store" => 1,
                    "personnel-ProcessRule-destroy" => 1,
                    "personnel-ProcessRule-index" => 1,
                    "personnel-ProcessRule-update" => 1,
                    "personnel-SupplementLeave-store" => 1,
                    "personnel-SupplementLeave-index" => 2,
                    "personnel-SupplementLeave-show" => 1,
                    "personnel-SupplementLeave-wait" => 1,
                    "personnel-SupplementLeave-update" => 1,
                    "personnel-SupplementSign-store" => 1,
                    "personnel-SupplementSign-index" => 2,
                    "personnel-SupplementSign-show" => 1,
                    "personnel-SupplementSign-wait" => 1,
                    "personnel-SupplementSign-update" => 1,
                    "personnel-SupplementPurchase-store" => 1,
                    "personnel-SupplementPurchase-index" => 2,
                    "personnel-SupplementPurchase-show" => 1,
                    "personnel-SupplementPurchase-wait" => 1,
                    "personnel-SupplementPurchase-update" => 1,
                    "personnel-Attendance-index" => 1,
                    "personnel-Attendance-show" => 1,
                    "personnel-Attendance-showInfo" => 1,
                    "personnel-Attendance-import" => 1,
                    "personnel-Attendance-update" => 1,
                    "personnel-TrainClass-index" => 1,
                    "personnel-TrainClass-store" => 1,
                    "personnel-TrainClass-show" => 1,
                    "personnel-TrainClass-update" => 1,
                    "personnel-TrainClass-destroy" => 1,
                    "personnel-TrainPersonnel-index" => 1,
                    "personnel-TrainPersonnel-join" => 1,
                    "personnel-TrainPersonnel-score" => 1,
                    "personnel-TrainPersonnel-commentlist" => 1,
                    "personnel-TrainPersonnel-commentcensus" => 1,
                    "personnel-TrainPersonnel-commentedit" => 1,
                    "personnel-TrainPersonnel-commentadd" => 1,
                    "personnel-TrainResource-index" => 1,
                    "personnel-TrainResource-update" => 1,
                ]),
                'department_id' => 2,
                'company_id' => 1
            ],
        ];

        $posts = $this->table('position');
        $posts->insert($data)
            ->save();
    }
}