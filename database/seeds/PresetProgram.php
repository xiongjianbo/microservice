<?php

use think\migration\Seeder;

class PresetProgram extends Seeder
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
                'type' => '1',
                'parent_id' => '0',
                'company_id' => '1',
                'name' => 'Web网站'
            ],
            [
                'type' => '1',
                'parent_id' => '0',
                'company_id' => '1',
                'name' => '移动应用iOS'
            ],
            [
                'type' => '1',
                'parent_id' => '0',
                'company_id' => '1',
                'name' => '移动应用Android'
            ],
            [
                'type' => '1',
                'parent_id' => '0',
                'company_id' => '1',
                'name' => '微信小程序'
            ],
            [
                'type' => '2',
                'parent_id' => '1',
                'company_id' => '1',
                'name' => '基本功能'
            ],
            [
                'type' => '2',
                'parent_id' => '1',
                'company_id' => '1',
                'name' => '高级功能'
            ],
            [
                'type' => '2',
                'parent_id' => '1',
                'company_id' => '1',
                'name' => '电商功能'
            ],
            [
                'type' => '3',
                'parent_id' => '5',
                'company_id' => '1',
                'name' => '注册登录'
            ],
            [
                'type' => '3',
                'parent_id' => '5',
                'company_id' => '1',
                'name' => '第三方登录'
            ],
            [
                'type' => '3',
                'parent_id' => '6',
                'company_id' => '1',
                'name' => '音乐视频'
            ],
            [
                'type' => '4',
                'parent_id' => '8',
                'company_id' => '1',
                'name' => '邮箱',
                'price_min' => '500',
                'price_max' => '1000',
                'day_min' => '2',
                'day_max' => '5',
            ],
            [
                'type' => '4',
                'parent_id' => '8',
                'company_id' => '1',
                'name' => '手机',
                'price_min' => '300',
                'price_max' => '1000',
                'day_min' => '2',
                'day_max' => '5',
            ],
            [
                'type' => '4',
                'parent_id' => '8',
                'company_id' => '1',
                'name' => '密码找回',
                'price_min' => '300',
                'price_max' => '500',
                'day_min' => '2',
                'day_max' => '5',
            ],

        ];
        $this->table('preset_program')->insert($data)->save();
    }
}