<?php

use think\migration\Seeder;

class SalaryMeritsLevel extends Seeder
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
                'plan_id' => "2",
                'level_id' => "1",
                'bonus' => "100",
                'company_id' => "1",
            ],
            [
                'plan_id' => "2",
                'level_id' => "2",
                'bonus' => "110",
                'company_id' => "1",
            ],
            [
                'plan_id' => "2",
                'level_id' => "3",
                'bonus' => "120",
                'company_id' => "1",
            ],
        ];
        $this->table('salary_merits_level')->insert($data)->save();
    }
}