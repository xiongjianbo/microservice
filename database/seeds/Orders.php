<?php

use think\migration\Seeder;

class Orders extends Seeder
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
                'number' => 'DD201904250001',
                'company_id' => '1',
                'customer_id' => '20001',
                'project_type_id' => '1',
                'name' => 'PMC项目管理系统',
                'expect_date' => '1556585287',
                'expect_money' => '200000',
                'currency_id' => '1',
                'personnel_id' => '1',
                'pay_type' => '1',
                'instructions' => '这里是说明'
            ]
        ];
        $this->table('orders')->insert($data)->save();
    }
}