<?php

use think\migration\Seeder;

class ProjectDelivery extends Seeder
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
                'number' => 'JF201905061800001234',
                'project_id'=>1,
                'is_collection'=>0,
                'commit_date'=>1557192801,
                'comment'=>'项目交付测试',
                'status'=>0,
            ],
            [
                'number' => 'JF201905062000002222',
                'project_id'=>2,
                'is_collection'=>1,
                'commit_date'=>1557193801,
                'comment'=>'项目交付测试2',
                'status'=>0,
            ],
        ];
        $this->table('project_delivery')->insert($data)->save();
    }
}