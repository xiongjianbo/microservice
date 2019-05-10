<?php

namespace app\asset\validate;

use think\Validate;

class Supplier extends Validate
{
	protected $rule = [
        'id' => 'require|integer|max:11',
	    'name'  =>'max:50',
	    'abbreviation'  =>'max:30',
	    'address'  =>'max:150',
	    'type'  =>'max:50',
	    'tel'  =>'max:20',
	    'email'  =>'max:50',
	    'contact'  =>'isContact',
    ];


	protected $scene = [
        'index' => ['name','abbreviation'],
        'show' => ['id'],
    ];

    // 验证场景定义
    public function sceneStore()
    {
        return $this->append('name', 'require')
            ->append('type', 'require')
            ->remove('id');
    }

    // 判断联系人格式
    public function isContact($array){

        foreach ($array as $item) {
            if(!array_key_exists('name',$item)){
                return false;
            }
        }
        return true;
    }
}
