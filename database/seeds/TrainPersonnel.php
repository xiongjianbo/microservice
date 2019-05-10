<?php

use think\migration\Seeder;
use Faker\Factory;

class TrainPersonnel extends Seeder
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
        $trainPersonnel = $this->table('train_personnel');

        $trainClassArray = db('train_class')->column('id');
        if(count($trainClassArray) == 0){
            $trainClassArray = [1,2,3,4,5,6,7,8,9,10];
        }

        $personnelArray = db('personnel')->column('id');
        if(count($personnelArray) == 0){
            $personnelArray = [1,2,3,4,5,6,7,8,9,10];
            $total = 10;
        }else{
            $total = count($personnelArray);
        }

        for($begin = 0;$begin < $total;$begin++){
            $score = $faker->numberBetween(-1,0);
            $teacher_evaluation = $demand_evaluation = $train_evaluation = $evaluation_time = $join_time = null;

            if($score != -1){
                $score = rand(60,99);
                $teacher_evaluation = rand(1,3);
                $demand_evaluation = rand(1,3);
                $evaluation_time = $faker->time('Y-m-d H:i:s');
                $join_time = $faker->time('Y-m-d H:i:s');
            }

            $data[] = [
                'train_id'      => $faker->randomElement($trainClassArray,1),
                'personnel_id'  => $faker->randomElement($personnelArray,1),
                'score'    => $score,
                'teacher_evaluation'    => $teacher_evaluation,
                'demand_evaluation'    => $demand_evaluation,
                'evaluation_time'    => $evaluation_time,
                'join_time'    => $join_time,
            ];
        }

        $trainPersonnel->insert($data)->save();
    }
}
