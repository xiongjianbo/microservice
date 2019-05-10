<?php

use think\migration\Seeder;

class Config extends Seeder
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
                'title' => '请假类型',
                'name' => 'LEAVE_TYPE',
                'value' => json_encode([1 => "事假", 2 => "病假", 3 => "调休"]),
            ],
            [
                'title' => '请假类型薪资扣除方式',
                'name' => 'LEAVE_TYPE_STYLE',
                'value' => json_encode([1 => 1, 2 => 2, 3 => 3]),
            ],
        ];

        $posts = $this->table('config');
        $posts->insert($data)
            ->save();
    }
}