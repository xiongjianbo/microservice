<?php

use think\migration\Seeder;

class OrderPay extends Seeder
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
                'type' => '1',
                'order_id' => '1',
                'expect_data' => '1556585287',
                'expect_money' => '200000',
                'expect_money' => '200000',
                'currency_id' => '1'
            ]
        ];
        $this->table('order_pay')->insert($data)->save();
    }
}