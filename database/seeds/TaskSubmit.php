<?php

use think\migration\Seeder;

class TaskSubmit extends Seeder
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
                'description' => '',
                'task_id' => 1,
                'status' => 0,
                'personnel_id' => 1,
            ],
        ];
        $this->table('task_submit')->insert($data)->save();
    }
}