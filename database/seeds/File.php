<?php

use think\migration\Seeder;

class File extends Seeder
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
                'name' => 'file'.rand(1111,9999),
                'resource' => 'task',
                'resource_id' => 1,
                'router_id'=>1,
            ],
            [
                'name' => 'file'.rand(1111,9999),
                'resource' => 'task',
                'resource_id' => 2,
                'router_id'=>1,
            ],
        ];
        $this->table('file')->insert($data)->save();
    }
}