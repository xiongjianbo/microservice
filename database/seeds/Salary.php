<?php

use think\migration\Seeder;

class Salary extends Seeder
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
                'basic' => '3000',
                'position' => '2000',
                'traffic' => '200',
                'other' => '100',
                'currency' => '1',
                'company_id' => '1',
                "plan_id" => '1',
                'level_id' => '1',
            ],
            [
                'basic' => '3100',
                'position' => '2200',
                'traffic' => '200',
                'other' => '100',
                'currency' => '2',
                'company_id' => '1',
                "plan_id" => '1',
                'level_id' => '2',
            ],
            [
                'basic' => '3100',
                'position' => '2200',
                'traffic' => '200',
                'other' => '100',
                'currency' => '3',
                'company_id' => '1',
                "plan_id" => '1',
                'level_id' => '3',
            ],
            [
                'basic' => '3100',
                'position' => '2200',
                'traffic' => '200',
                'other' => '100',
                'currency' => '2',
                'company_id' => '1',
                "plan_id" => '1',
                'level_id' => '4',
            ],
            [
                'basic' => '3100',
                'position' => '2200',
                'traffic' => '200',
                'other' => '100',
                'currency' => '2',
                'company_id' => '1',
                "plan_id" => '1',
                'level_id' => '5',
            ],
        ];
        $this->table('salary')->insert($data)->save();
    }
}