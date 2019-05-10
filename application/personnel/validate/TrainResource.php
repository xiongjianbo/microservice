<?php

namespace app\personnel\validate;

use think\Validate;

class TrainResource extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
	    'id'    => 'require|integer',
        'photo' => 'array|isCorrect',
        'video' => 'array|isCorrect',
        'file' => 'array|isCorrect',
    ];

    protected $scene = [
        'index' => ['id'],
        'update'=>['id','video','photo','file']
    ];

    // 判断指定的键名是否存在
    public function isCorrect($array){

        foreach ($array as $item) {
           if(!(array_key_exists('icon',$item)
               && array_key_exists('name',$item)
               && array_key_exists('address',$item))
           ){
               return false;
           }

        }
        return true;
    }
}
