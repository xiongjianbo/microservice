<?php

use think\migration\Seeder;
use Faker\Factory;

class TrainResource extends Seeder
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
        $trainResources = $this->table('train_resource');

        $trainClassArray = db('train_class')
            ->order('id','desc')
            ->column('id');
        if(count($trainClassArray) == 0){
            $trainClassArray = [1,2,3,4,5,6,7,8,9,10];
            $total = 10;
        }else{
            $total = count($trainClassArray);
        }

        for($begin = 0;$begin < $total;$begin++){
            $photoArray = $videoArray = $fileArray = [];
            for($start = 0;$start < rand(0,4);$start++){
                $videoArray[] = [
                    'icon'  => $faker->imageUrl(),
                    'name'  => $faker->name,
                    'address'  => $faker->imageUrl(),
                ];

            }

            for($start = 0;$start < rand(0,4);$start++){
                $fileArray[] = [
                    'icon'  => $faker->imageUrl(),
                    'name'  => $faker->name,
                    'address'  => $faker->imageUrl(),
                ];
            }

            for($start = 0;$start < rand(0,4);$start++){
                $photoArray[] = [
                    'icon'  => $faker->imageUrl(),
                    'name'  => $faker->name,
                    'address'  => $faker->imageUrl(),
                ];
            }


            $data[] = [
                'train_id' => $trainClassArray[$begin],
                'photo'    => json_encode($photoArray),
                'video'    => json_encode($videoArray),
                'file'     => json_encode($fileArray),
            ];
        }

        $trainResources->insert($data)->save();
    }
}
