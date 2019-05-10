<?php

namespace app\personnel\validate;

use think\Validate;

class TrainClass extends Validate
{

    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'id' => 'require|integer|max:11',
	    'name'  => 'require|max:255',
        'begin_time' => 'require|dateFormat:Y-m-d H:i',
        'teacher'   => 'require|max:255',
        'address'   => 'require|max:255',
        'is_all'   => 'require|bool',
        'target'   => 'array|isCorrectTarget',
        'join_type' =>'require|between:1,3',
        'time_length' => 'require|max:30',
        'most_personnel'    => 'require|integer|max:11|isPositiveNumber',
        'current_status'    => 'require|between:2,4',
        'description'       => 'require',
        'personnel_id'      => 'integer'
    ];

    protected $scene = [
        'index' => ['personnel_id'],
        'show' => ['id'],
        'destroy' => ['id']
    ];

    public function sceneUpdate(){
        return $this->remove('is_all')
            ->remove('join_type');
    }

    // 验证场景定义
    public function sceneStore()
    {
        return $this->remove('id')
            ->append('current_status','=:2');
    }

    // 验证参与人数是不是大于0
    protected function isPositiveNumber($int){
        if($int >= 0){
            return true;
        }
        return false;
    }

    // 验证传入是不是数字
    protected  function isCorrectTarget($array){

        foreach ($array as $item){

            if(!is_numeric($item)){
                return false;
            }
        }
        return true;
    }
}
