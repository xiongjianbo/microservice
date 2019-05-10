<?php

use think\migration\Seeder;

class SalaryMerits extends Seeder
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
                'plan_id'=>'2',
                'type'=>'1',
                'range_key'=>'1',
                'merits'=>'100',
                'company_id'=>'1',
            ],
            [
                'plan_id'=>'2',
                'type'=>'1',
                'range_key'=>'10',
                'merits'=>'200',
                'company_id'=>'1',
            ],
            [
                'plan_id'=>'2',
                'type'=>'1',
                'range_key'=>'20',
                'merits'=>'300',
                'company_id'=>'1',
            ],
            [
                'plan_id'=>'2',
                'type'=>'1',
                'range_key'=>'30',
                'merits'=>'400',
                'company_id'=>'1',
            ],
            [
                'plan_id'=>'2',
                'type'=>'1',
                'range_key'=>'40',
                'merits'=>'500',
                'company_id'=>'1',
            ],
        ];
        $this->table('salary_merits')->insert($data)->save();
    }
}