<?php

use think\migration\Seeder;

class Program extends Seeder
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
                'program_id' => '11',
                'module_id' => '8',
                'category_id' => '5',
                'platform_id' => '1',
                'price' => '10000',
            ],
            [
                'company_id' => '1',
                'source_type' => '1',
                'source_id' => '1',
                'program_id' => '12',
                'module_id' => '8',
                'category_id' => '5',
                'platform_id' => '1',
                'price' => '10000',
            ],
            [
                'company_id' => '1',
                'source_type' => '2',
                'source_id' => '1',
                'program_id' => '11',
                'module_id' => '8',
                'category_id' => '5',
                'platform_id' => '1',
                'price' => '10000',
            ],
            [
                'company_id' => '1',
                'source_type' => '2',
                'source_id' => '1',
                'program_id' => '12',
                'module_id' => '8',
                'category_id' => '5',
                'platform_id' => '1',
                'price' => '10000',
            ],
        ];
        $this->table('program')->insert($data)->save();
    }
}