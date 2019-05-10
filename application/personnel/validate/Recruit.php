<?php

namespace app\personnel\validate;

use think\Validate;

class Recruit extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'            => 'require|integer',
        'name'          => 'require|max:30',
        'phone'         => 'require|max:30',
        'resume_uri'    => 'require|max:255',
        'status'        => 'require|integer|isCorrectStatus',
        'personnel_id'  => 'integer'
    ];

	protected $scene = [
      'innerstore'  => ['name','phone','resume_uri'],
      'innerupdatestatus' => ['id','status'],
      'innerdestroy' => ['id'],
    ];

	// 自定义场景
    public function sceneInnerindex(){
        return $this->remove(['name','phone','resume_uri','id'])
            ->remove('status','require')
            ;
    }
    /**
     * 查看状态选择是否正确
     *
     * @param $int
     * @return bool
     */
    protected function isCorrectStatus($int){
        $validatedArr = [1,2,3,4];
        if(in_array($int,$validatedArr)){
            return true;
        }
        return false;
    }
}
