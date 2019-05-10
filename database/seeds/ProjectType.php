<?php

use think\migration\Seeder;

class ProjectType extends Seeder
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
                'name' => '软件',
                'sort' => 1,
            ],
            [
                'parent_id' => 1,
                'name' => '应用软件',
                'sort' => 1,
            ],
            [
                'parent_id' => 1,
                'name' => 'APP软件',
                'sort' => 1,
            ],
            [
                'parent_id' => 0,
                'name' => '动画',
                'sort' => 2,
            ],
        ];
        $this->table('project_type')->insert($data)->save();
    }
}