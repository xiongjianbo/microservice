<?php

use think\migration\Seeder;

class SalaryInsurance extends Seeder
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
                'company_id' => '1',
                'name' => '医疗保险费',
                'type' => '2',
                'personal' => '20',
                'company' => '80',
            ],
            [
                'company_id' => '1',
                'name' => '养老保险费',
                'type' => '2',
                'personal' => '20',
                'company' => '80',
            ],
            [
                'company_id' => '1',
                'name' => '失业保险费',
                'type' => '2',
                'personal' => '20',
                'company' => '80',
            ],
            [
                'company_id' => '1',
                'name' => '生育保险费',
                'type' => '2',
                'personal' => '20',
                'company' => '80',
            ],
            [
                'company_id' => '1',
                'name' => '大额医疗保险费',
                'type' => '2',
                'personal' => '20',
                'company' => '80',
            ],
            [
                'company_id' => '1',
                'name' => '住房公积金',
                'type' => '1',
                'personal' => '400',
                'company' => '400',
            ],
        ];
        $this->table('salary_insurance')->insert($data)->save();
    }
}