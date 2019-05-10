<?php

namespace app\personnel\validate;

use think\Validate;

class Notice extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'    => 'require|integer',
        'title' => 'require|max:255',
        'begin_time' => 'require|dateFormat:Y-m-d H:i:s',
        'content' => 'require',
        'is_all' => 'require|integer|isCorrect',
        'personnel' => 'array|isCorrectTarget',
        'join_personnel_id' => 'integer',
        'personnel_id'  => 'integer',
        'is_read'  => 'require|integer|isCorrect',
    ];

	protected $scene = [
	    'index'     => ['join_personnel_id','personnel_id'],
	    'show'     => ['id'],
        'store'     => ['title','begin_time','content','personnel','is_all'],
        'destroy'     => ['id'],
        'read'     => ['id'],
        'readdetail'     => ['id','is_read'],
    ];

	// 是否是全员,1是,0不是 枚举类型
	protected function isCorrect($int){
	    $int = (int)$int;
	    if($int === 0 || $int === 1){
	        return true;
        }
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
