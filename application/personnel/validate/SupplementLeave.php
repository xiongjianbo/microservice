<?php

namespace app\personnel\validate;

use think\Validate;

class SupplementLeave extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'type'  =>'require|between:1,10',
	    'start_time'  =>'require|dateFormat:Y-m-d H:i:s',
	    'end_time'  =>'require|dateFormat:Y-m-d H:i:s',
	    'reason'  =>'require',
	    'attachment_uri'  =>'array',
	    'time_len'  =>'require|array|isCorrectTimeLen',
	    'verify_content'  =>'require',
	    'verify_status'  =>'require|isCorrect',
        'current_status' => 'require|between:-1,1',
        'personnel_id' => 'integer',
        'department_id' => 'integer',
        'id' => 'integer|require',
    ];


	protected $scene = [
	    'store'   => ['type','start_time','end_time','reason','attachment_uri','time_len'],
        'index' => ['personnel_id','department_id'],
        'show' => ['id'],
        'wait' => ['current_status'],
        'update' => ['id','verify_status','verify_content'],
    ];

    // 判断指定的键名是否存在
    public function isCorrectTimeLen($array){

        foreach ($array as $item) {
            if(!(array_key_exists('day',$item)
                && array_key_exists('hour',$item))
            ){
                return false;
            }

        }
        return true;
    }

	// 验证审批结果是否正确 1通过,-1不通过
	protected function isCorrect($status){
	    if($status == -1 || $status == 1){
	        return true;
        }
	    return false;
    }
}
