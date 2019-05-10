<?php

use think\migration\Seeder;

class SalaryPlan extends Seeder
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
                'type' => '1',
                'name' => '总经理底薪',
                'company_id' => '1',
                'apply' => "1",
                'apply_id' => "1",
            ],
            [
                'type' => '2',
                'name' => '总经理奖金方案',
                'company_id' => '1',
                'apply' => "2",
                'apply_id' => "1",
            ],
            [
                'type' => '3',
                'name' => '总经理扣款方案',
                'company_id' => '1',
                'apply' => "2",
                'apply_id' => "1",
            ],
        ];
        $this->table('salary_plan')->insert($data)->save();
    }
}