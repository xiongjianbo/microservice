<?php

use think\migration\Seeder;

class SalaryLevel extends Seeder
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
                'position_id'=>'1',
                'name'=>'初级',
                'level'=>'1',
                'company_id'=>'1',
            ],
            [
                'position_id'=>'1',
                'name'=>'中级',
                'level'=>'2',
                'company_id'=>'1',
            ],
            [
                'position_id'=>'1',
                'name'=>'高级',
                'level'=>'3',
                'company_id'=>'1',
            ],
            [
                'position_id'=>'2',
                'name'=>'初级',
                'level'=>'1',
                'company_id'=>'1',
            ],
            [
                'position_id'=>'2',
                'name'=>'中级',
                'level'=>'2',
                'company_id'=>'1',
            ],
            [
                'position_id'=>'2',
                'name'=>'高级',
                'level'=>'3',
                'company_id'=>'1',
            ],
        ];
        $this->table('salary_level')->insert($data)->save();
    }
}