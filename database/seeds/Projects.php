<?php

use think\migration\Seeder;

class Projects extends Seeder
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
                'order_id' => '1',
                'number' => 'XM201905061056221111',
                'company_id' => '1',
                'customer_id' => '20001',
                'project_type_id' => '1',
                'name' => 'PMC项目管理系统',
                'done_date' => '1556585287',
                'begin_date' => '1556585287',
                'expect_date' => '1556585287',
                'expect_money' => '200000',
                'currency_id' => '1',
                'personnel_id' => '1',
                'instructions' => '这里是说明'
            ],
            [
                'order_id' => '2',
                'number' => 'XM201905061056222222',
                'company_id' => '1',
                'customer_id' => '1',
                'project_type_id' => '1',
                'name' => 'abc管理系统',
                'done_date' => '1557194162',
                'begin_date' => '1557194162',
                'expect_date' => '1557194162',
                'expect_money' => '300000',
                'currency_id' => '1',
                'personnel_id' => '1',
                'instructions' => '这里是说明222'
            ],
            [
                'parent_id' => '1',
                'number' => 'XM201905071056222222',
                'company_id' => '1',
                'customer_id' => '20001',
                'project_type_id' => '1',
                'name' => 'pmc子项目',
                'done_date' => '1557194162',
                'begin_date' => '1557194162',
                'expect_date' => '1557194162',
                'expect_money' => '300000',
                'currency_id' => '1',
                'personnel_id' => '1',
                'instructions' => '这里是说明333'
            ],
        ];
        $this->table('projects')->insert($data)->save();
    }
}