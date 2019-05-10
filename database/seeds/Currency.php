<?php

use think\migration\Seeder;

class Currency extends Seeder
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
                'code' => '￥',
                'name' => 'CNY',
                'desc' => '人民币',
                'exchange_rate' => '0',
                'gst_rate' => '0',
                'fees_gst_rate' => '0',
                'exchange_loss' => '0',
            ],
            [
                'code' => '$',
                'name' => 'USD',
                'desc' => '美元',
                'exchange_rate' => '0',
                'gst_rate' => '0',
                'fees_gst_rate' => '0',
                'exchange_loss' => '0',
            ],
            [
                'code' => '￥',
                'name' => 'JPY',
                'desc' => '日元',
                'exchange_rate' => '0',
                'gst_rate' => '0',
                'fees_gst_rate' => '0',
                'exchange_loss' => '0',
            ],
        ];

        $this->table('currency')
            ->insert($data)
            ->save();
    }
}