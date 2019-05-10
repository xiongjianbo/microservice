<?php

use think\migration\Seeder;
use Faker\Factory;

class TrainClass extends Seeder
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
        $data = [];
        $faker = Factory::create('zh_CN');
        $trainClass = $this->table('train_class');

        $departmentArr = db('department')->column('id');
        $companyArr = db('company')->column('id');
        if(count($companyArr) == 0){
            $companyArr = [1];
        }
        if(count($departmentArr) == 0){
            $departmentArr = [1,2,3,4,5,6,7,8,9,10];
            $total = 10;
        }else{
            $total = count($departmentArr);
        }

        for($begin = 0;$begin < $total;$begin++){
            $target = [
                'is_all'     => $faker->boolean,
                'personnel' => $faker->randomElements($departmentArr,5)
            ];
            $data[] = [
                'company_id'    =>array_rand($companyArr),
                'name'          => $faker->text(20),
                'begin_time'    => $faker->time('Y-m-d H:i:s'),
                'teacher'    => $faker->name(),
                'address'    => $faker->address,
                'join_type'    => rand(1,3),
                'target'    => json_encode($target),
                'time_length'    => rand(1,3).'小时',
                'most_personnel'    => rand(0,30),
                'current_status'    => rand(2,4),
                'description'    => $faker->realText(30,4),
            ];
        }

        $trainClass->insert($data)->save();
    }
}
