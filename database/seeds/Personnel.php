<?php

use think\migration\Seeder;

class Personnel extends Seeder
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
                "username" => "cgland_admin",
                'password' => 'd93a5def7511da3d0f2d171d9c344e91',
                'type' => 0,
                "position_id" => 0,
                "department_id" => 0,
                "company_id" => 1,
                "name" => '动画谷超级管理员',
            ],
            [
                "username" => "zhangrui",
                'password' => 'd93a5def7511da3d0f2d171d9c344e91',
                'type' => 1,
                "number" => 42,
                "position_id" => 3,
                "department_id" => 2,
                "company_id" => 1,
                "name" => '张芮',
            ],
            [
                "username" => "lijie",
                'password' => 'd93a5def7511da3d0f2d171d9c344e91',
                'type' => 1,
                "number" => 26,
                "position_id" => 2,
                "department_id" => 8,
                "company_id" => 1,
                "name" => '李杰',
            ],
            [
                "username" => "chendezhi",
                'password' => 'd93a5def7511da3d0f2d171d9c344e91',
                'type' => 1,
                "number" => 20,
                "position_id" => 1,
                "department_id" => 6,
                "company_id" => 1,
                "name" => '陈德智',
            ],
            [
                "username" => "zhaoming",
                'password' => 'd93a5def7511da3d0f2d171d9c344e91',
                'type' => 1,
                "number" => 20,
                "position_id" => 1,
                "department_id" => 6,
                "company_id" => 1,
                "name" => '赵明',
            ],
//            ["number" => 1, "name" => "贾总", "department_id" => 1],
//            ["number" => 3, "name" => "刘玉卢玺", "department_id" => 2],
//            ["number" => 4, "name" => "施玉琼", "department_id" => 3],
//            ["number" => 5, "name" => "何斌", "department_id" => 4],
//            ["number" => 6, "name" => "陈思", "department_id" => 3],
//            ["number" => 7, "name" => "赵红果", "department_id" => 2],
//            ["number" => 8, "name" => "罗钦瑞", "department_id" => 2],
//            ["number" => 9, "name" => "贾婷", "department_id" => 2],
//            ["number" => 10, "name" => "徐光达", "department_id" => 2],
//            ["number" => 11, "name" => "许峰", "department_id" => 2],
//            ["number" => 12, "name" => "何江", "department_id" => 5],
//            ["number" => 13, "name" => "黄华宇", "department_id" => 5],
//            ["number" => 14, "name" => "刘璇", "department_id" => 2],
//            ["number" => 15, "name" => "钟雨航", "department_id" => 5],
//            ["number" => 16, "name" => "余豪", "department_id" => 5],
//            ["number" => 18, "name" => "吕启明", "department_id" => 6],
//            ["number" => 19, "name" => "李承昊", "department_id" => 6],
//            ["number" => 20, "name" => "陈德智", "department_id" => 7],
//            ["number" => 21, "name" => "王子亮", "department_id" => 7],
//            ["number" => 22, "name" => "朱俊恒", "department_id" => 5],
//            ["number" => 23, "name" => "代华龙", "department_id" => 7],
//            ["number" => 24, "name" => "王二虎", "department_id" => 7],
//            ["number" => 25, "name" => "张倩如", "department_id" => 8],
//            ["number" => 26, "name" => "李杰", "department_id" => 7],
//            ["number" => 27, "name" => "陆迅", "department_id" => 5],
//            ["number" => 28, "name" => "李明娇", "department_id" => 9],
//            ["number" => 29, "name" => "余龙", "department_id" => 9],
//            ["number" => 30, "name" => "欧孝文", "department_id" => 3],
//            ["number" => 31, "name" => "张丁浩", "department_id" => 10],
//            ["number" => 32, "name" => "陶霜", "department_id" => 9],
//            ["number" => 33, "name" => "蔡兴业", "department_id" => 5],
//            ["number" => 36, "name" => "马晓淋", "department_id" => 10],
//            ["number" => 37, "name" => "王瑞宇", "department_id" => 3],
//            ["number" => 38, "name" => "陈清", "department_id" => 11],
//            ["number" => 41, "name" => "熊昶", "department_id" => 10],
//            ["number" => 42, "name" => "张芮", "department_id" => 12],
//            ["number" => 43, "name" => "潘栗炜", "department_id" => 12],
//            ["number" => 45, "name" => "郑鹏程", "department_id" => 9],
//            ["number" => 46, "name" => "郑文浩", "department_id" => 2],
//            ["number" => 47, "name" => "邓友明", "department_id" => 4],
//            ["number" => 48, "name" => "庞唯", "department_id" => 5],
//            ["number" => 51, "name" => "许媛媛", "department_id" => 5],
//            ["number" => 52, "name" => "宋瑜轩", "department_id" => 5],
//            ["number" => 53, "name" => "鲁宝莹", "department_id" => 9],
//            ["number" => 54, "name" => "陈屾尧", "department_id" => 9],
//            ["number" => 55, "name" => "范涛", "department_id" => 13],
//            ["number" => 57, "name" => "熊建波", "department_id" => 4],
//            ["number" => 58, "name" => "康志鹏", "department_id" => 5],
//            ["number" => 60, "name" => "何三江", "department_id" => 3],
//            ["number" => 61, "name" => "曾玥", "department_id" => 8],
        ];

        $users = $this->table('personnel');
        $users->insert($data)
            ->save();
    }
}