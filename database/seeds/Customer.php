<?php

use think\migration\Seeder;

class Customer extends Seeder
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
                'id' => 20001,
                'name' => '成都动画谷数码科技有限公司',
                'abbreviation' => '动画谷',
                'tel' => '15112313147',
                'email' => 'xxx@xxx.com',
                'statement_date' => 31,
                'type' => 1,
                'company_id' => 1,
                'address' => '锦江区XXX大街',
            ],
            [
                'id' => 20002,
                'name' => 'XXX有限公司',
                'abbreviation' => 'XXX公司',
                'tel' => '15112313147',
                'email' => 'xxx@xxx.com',
                'statement_date' => 31,
                'type' => 2,
                'company_id' => 1,
                'address' => 'XXX区XXX大街',
            ],
        ];

        $this->table('customer')
            ->insert($data)
            ->save();
    }
}