<?php

use think\migration\Seeder;

class TaskType extends Seeder
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
                'parent_id' => 0,
                'name' => '准备任务',
                'sort' => 1,
            ],
            [
                'parent_id' => 0,
                'name' => '制作任务',
                'sort' => 2,
            ],
            [
                'parent_id' => 0,
                'name' => '修改任务',
                'sort' => 3,
            ],
        ];
        $this->table('task_type')->insert($data)->save();
    }
}