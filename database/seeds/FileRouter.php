<?php

use think\migration\Seeder;

class FileRouter extends Seeder
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
                'domain' => '149.129.52.151:2002',
            ]
        ];
        $this->table('file_router')->insert($data)->save();
    }
}