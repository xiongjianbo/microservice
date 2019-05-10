<?php

use think\migration\Seeder;

class Language extends Seeder
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
                'chinese'=>1,
                'english'=>1
            ],
            [
                'company_id' => '2',
                'chinese'=>0,
                'english'=>1,
                'japanese'=>1
            ],
        ];
        $this->table('language')->insert($data)->save();

    }
}