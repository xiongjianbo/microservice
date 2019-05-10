<?php

namespace app\personnel\validate;

use think\Validate;

class TrainPersonnel extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'state'  => 'require|isCorrect',
        'id'     => 'require|integer',
        'score_info' => 'array|isCorrectScore',
        'teacher_evaluation' => 'require|isEvaluation',
        'demand_evaluation' => 'require|isEvaluation',
        'evaluation' => 'require',
        'type'  => 'require|isTypeCorrect'
    ];

    protected $scene = [
        'join'  => ['state','id'],
        'index' =>['id','type'],
        'score' => ['id','score_info'],
        'commentlist' => ['id'],
        'commentcensus' => ['id'],
        'commentedit' => ['id','teacher_evaluation','demand_evaluation'],
        'commentadd' => ['id','evaluation'],
    ];

    /**
     * 验证考试结果和列表的参数是否正确
     *
     * @param $string
     * @return bool
     */
    protected  function isTypeCorrect($string){
        if($string == 'result' || $string == 'index'){
            return true;
        }
        return false;
    }


    /**
     * 验证传入是不是数字
     *
     * @param $array
     * @return bool
     */
    protected  function isCorrectScore($array){

        foreach ($array as $item) {
            if(!(array_key_exists('personnel_id',$item)
                && array_key_exists('score',$item))
            ){
                return false;
            }
            if(!is_numeric($item['personnel_id'])){
                return false;
            }
            if(!is_numeric($item['score'])){
                return false;
            }
            if( !($item['score'] >= 0 && $item['score'] <= 100)){
                return false;
            }
        }
        return true;
    }
    /**
     * 查询报名的状态是否是允许值
     *
     * @param $int 1参加，0取消
     * @return bool
     */
    public function isCorrect($int){
        if($int == 1 || $int == 0){
            return true;
        }
        return false;
    }

    /**
     * 查询评价的内容是否合法
     *
     * @param $int 1参加，0取消
     * @return bool
     */
    public function isEvaluation($int){
        if($int >= 1 && $int <= 3){
            return true;
        }
        return false;
    }

}
