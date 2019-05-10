<?php

namespace app\personnel\validate;

use think\Validate;

class ProcessRule extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'  => 'require|integer',
	    'p_id'  => 'require|integer',
	    'type'  => 'require|isTypeCorrect',
	    'level'  => 'require|isLevelCorrect',
	    'target'  => 'require|array|isCorrectTarget',
    ];


	protected $scene = [
	    'store'     => ['p_id','type','level','target'],
	    'destroy'     => ['id'],
	    'update'     => ['id','level','target'],
	    'index'     => ['id'],
    ];

    // 验证传入是不是数字
    protected  function isCorrectTarget($array){

        foreach ($array as $item){

            if(!is_numeric($item)){
                return false;
            }
        }
        return true;
    }

    /**
     * 判断Level 是否正确  1个人,2职位,3部门,4全员
     *
     * @param $level
     * @return bool
     */
    protected function isLevelCorrect($level){
        $arr =[1,2,3,4];
        return in_array($level,$arr);
    }

    /**
     * 判断type类型是否正确  1请假流程,2补卡流程,3采购申请路程,4人力申请流程,5任务提交审核流程
     *
     * @param $type
     * @return bool
     */
    protected function isTypeCorrect($type){
        $arr =[1,2,3,4,5];
        return in_array($type,$arr);
    }
}
