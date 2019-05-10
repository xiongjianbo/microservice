<?php

namespace app\personnel\model;

use think\Model;
use think\model\concern\SoftDelete;

class TrainPersonnel extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = 'timestamp';


    // 设置字段类型
    protected $type = [
        'id'    => 'integer',
        'train_id'  =>  'integer',
        'personnel_id'       =>  'integer',
        'score'  =>  'integer',
        'teacher_evaluation'      =>  'integer',
        'demand_evaluation'      =>  'integer',
    ];

    /**
     * 关联报名表
     *
     * @return \think\model\relation\belongsTo
     */
    public function joinedPersonnel(){
        return $this->belongsTo('TrainClass','train_id','id');
    }

    /**
     * 关联到员工信息表
     *
     * @return \think\model\relation\BelongsTo
     */
    public function personnelInfo(){
        return $this->belongsTo('app\common\model\Personnel','personnel_id');
    }


    /**
     * 获取打分的统计信息
     *
     * @param $trainId  培训ID
     * @param enum $column teacher_evaluation获取demand_evaluation
     * @return array|bool|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEvaluationPercent($trainId, $column){
        $columnArray = ['teacher_evaluation','demand_evaluation'];

        if(!in_array($column,$columnArray)){
            return false;
        }

        $map['train_id'] = $trainId;

        $data = $this
            ->field('count(*) as total,'.$column.' as evaluation')
            ->where("evaluation_time is not null")
            ->where($map)
            ->group($column)
            ->select();
        return $data;
    }
    /**
     * 报名参加一个培训课程
     *
     * @param int $trainId  课程ID
     * @param int|array $personnel 员工ID 获取员工ID的数组
     * @param int $state   1参加  0取消参数
     * @return bool|\think\db\Query
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function joinClass($trainId, $personnel,$state = 1){
        // 判断课程是否还存在
        $trainClassModel = new TrainClass();
        $has = $trainClassModel->get($trainId);
        if(is_null($has)){
            return  false;
        }

        if(empty($personnel)){
            return true;
        }

        if(is_array($personnel)){
            $total = count($personnel);
            $now = 0;
            foreach ($personnel as $item) {
                $result = $this->join($trainId,$item,$state);
                if($result){
                    $now += 1;
                }
            }
            if($now  == $total){
                return true;
            }
            return false;
        }else{
            return $this->join($trainId,$personnel,$state);
        }
    }


    /**
     * 报名参加一个课程
     *
     * @param int $trainId  课程ID
     * @param int $personnelId  员工ID
     * @param int $state    1参数，0取消
     * @return bool|\think\db\Query
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function join($trainId, $personnelId,$state = 1){
        $map['train_id'] = $trainId;
        $map['personnel_id'] = $personnelId;
        $has = $this->withTrashed()->where($map)->find();

        if($has){
            if($state == 1){
                if($has['delete_time']){
                    $this->restore(['id'=>$has['id']]);
                    return $this->save(['join_time'=>date('Y-m-d H:i:s')],['id'=>$has['id']]);
                }
                return true;
            }else{

                return $this->destroy($has['id']);
            }

        }else{
            if($state == 1){
                $joinArr = [
                    'join_time'=>date('Y-m-d H:i:s'),
                    'train_id' => $trainId,
                    'personnel_id' => $personnelId,
                ];
                return $this->save($joinArr);
            }
            return true;
        }

        return $this->allowField(true)->insert($map);
    }
}
