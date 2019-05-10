<?php

use think\migration\Seeder;

class SalaryDeduction extends Seeder
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
                'insurance' => '234',
                'insurance_type' => '1',
                'late' => '25',
                'late_type' => '2',
                'early' => '66',
                'early_type' => '1',
                'thing_leave' => '400',
                'thing_leave_type' => '2',
                'sick_leave' => '80',
                'sick_leave_type' => '2',
                'company_id' => '1',
                'plan_id' => '3'
            ]
        ];
        $this->table('salary_deduction')->insert($data)->save();
    }
}