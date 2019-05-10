<?php

use think\migration\Seeder;

class Department extends Seeder
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
            ['id' => '1', 'p_id' => '0', 'name' => '总经办', 'company_id' => 1],
            ['id' => '2', 'p_id' => '0', 'name' => '人力资源部', 'company_id' => 1],
            ['id' => '3', 'p_id' => '0', 'name' => '财务', 'company_id' => 1],
            ['id' => '4', 'p_id' => '0', 'name' => '营业', 'company_id' => 1],
            ['id' => '5', 'p_id' => '0', 'name' => '项目管理', 'company_id' => 1],
            ['id' => '6', 'p_id' => '0', 'name' => '美术事业部', 'company_id' => 1],
            ['id' => '7', 'p_id' => '0', 'name' => 'IT事业部', 'company_id' => 1],
            ['id' => '8', 'p_id' => '6', 'name' => '2D动作', 'company_id' => 1],
//            ['name' => '总经办'],
//            ['name' => 'live2D'],
//            ['name' => '营业'],
//            ['name' => '程序'],
//            ['name' => '3Dcg部门'],
//            ['name' => '3D动作'],
//            ['name' => '2D动作'],
//            ['name' => '进程管理'],
//            ['name' => '项目管理'],
//            ['name' => 'findbest'],
//            ['name' => '财务'],
//            ['name' => '人事总务'],
//            ['name' => '主美'],
        ];

        $posts = $this->table('department');
        $posts->insert($data)
            ->save();
    }
}