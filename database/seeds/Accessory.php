<?php

use think\migration\Seeder;

class Accessory extends Seeder
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
                'company_id' => '1',
                'source_type' => '1',
                'source_id' => '1',
                'file_id' => '1',
            ],
            [
                'company_id' => '1',
                'source_type' => '1',
                'source_id' => '1',
                'file_id' => '2',
            ],
            [
                'company_id' => '1',
                'source_type' => '2',
                'source_id' => '1',
                'file_id' => '3',
            ],
            [
                'company_id' => '1',
                'source_type' => '2',
                'source_id' => '1',
                'file_id' => '3',
            ],
            [
                'company_id' => '1',
                'source_type' => '3',
                'source_id' => '1',
                'file_id' => '3',
            ],
            [
                'company_id' => '1',
                'source_type' => '3',
                'source_id' => '1',
                'file_id' => '3',
            ]
        ];
        $this->table('accessory')->insert($data)->save();
    }
}