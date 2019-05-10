<?php

use think\migration\Seeder;

class SkillCategory extends Seeder
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
                'name' => '2D动作',
                'name_en' => '2D动作',
                'name_ko' => '2D动作',
                'name_ja' => '2D动作',
            ],
            [
                'name' => '3D动作',
                'name_en' => '3D动作',
                'name_ko' => '3D动作',
                'name_ja' => '3D动作',
            ],
            [
                'name' => '4D动作',
                'name_en' => '4D动作',
                'name_ko' => '4D动作',
                'name_ja' => '4D动作',
            ],
        ];

        $this->table('skill_category')
            ->insert($data)
            ->save();
    }
}