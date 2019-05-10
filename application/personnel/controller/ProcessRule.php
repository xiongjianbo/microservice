<?php

namespace app\personnel\controller;

use think\Db;
use think\Request;
use app\common\model\ProcessRule as Process;
use app\common\model\Personnel;
use app\common\model\Position;
use app\common\model\Department;

class ProcessRule
{
    private $param;

    private $process;

    public function __construct(Process $process, Request $request){
        $this->param = $request->param();
        $this->process = $process;
    }

    /**
     * 获取流程的详细信息
     *
     * @param $id
     * @param Personnel $personnel
     * @param Position $position
     * @param Department $department
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($id, Personnel $personnel, Position $position, Department $department){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['type','=',$id];

        $data = $this->process
                ->field('id,p_id,level,target')
                ->where($map)->select()->toArray();

        foreach ($data as $key => $value){
            $level = $value['level'];
            $detail = [];
            foreach ($value['target'] as $item){
                if($level == 1){
                    $info = $personnel->field('id,name,position_id,department_id')
                        ->with(['position'=>function($query){
                            $query->field('id,title as name');
                        },'department'=>function($query){
                            $query->field('id,name');
                        }])
                        ->get($item);
                }elseif($level == 2){
                    $info = $position->field('id,title as name,department_id')
                        ->with(['department'=>function($query){
                            $query->field('id,name');
                        }])
                        ->get($item);
                }elseif($level == 3){
                    $info = $department->field('id,name')->get($item);
                }else{
                    $info = [
                        'id'    => $this->param['company_id'],
                        'name'  => '全员'
                    ];
                }
                $detail[] = $info;
            }
            $data[$key]['detail'] = $detail;
        }

        $data = listToTree($data);

        return jsonResponse($data);
    }

    /**
     * 更新一个条规则流程信息
     *
     * @param $id
     * @param Personnel $personnel
     * @param Position $position
     * @param Department $department
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update($id, Personnel $personnel, Position $position, Department $department){
        $map[] = ['id','=',$id];
        $map[] = ['company_id','=',$this->param['company_id']];
        $has = $this->process->where($map)->find();

        if(is_null($has)){
            return jsonResponse([],'对应的规则不存在或者已删除!',404);
        }
        // 审批者必须针对个人
        $level = $this->param['level'];
        if($level != 1){
            return jsonResponse([],'审批者必选是员工ID',305);
        }

        if($has['p_id'] == 0){
            // 修改发起者
            Db::startTrans();
            try{
                $this->deleteOld($has['type'],$this->param['level'],$this->param['target'],$id);
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                return jsonResponse([],'重复添加发起者更新老数据失败!',303);
            }
        }
        $target = $this->isLegal($this->param['level'],$this->param['target'],$personnel,$position,$department);
        if($target === false){
            return jsonResponse([],'所传入的target中没有合法值',405);
        }
        $this->param['target'] = $target;
        $result = $this->process->allowField(true)
                    ->save($this->param,['id'=>$id]);
        if($result){
            return jsonResponse();
        }
        return jsonResponse([],'数据库写入失败!',301);
    }

    /**
     * 新增一个审批规则节点
     *
     * @param Personnel $personnel
     * @param Position $position
     * @param Department $department
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function store(Personnel $personnel, Position $position, Department $department){

        $p_id = $this->param['p_id'];
        if($p_id != 0){
            // 判断对应的父ID是否存在
            $has = $this->process->get($p_id);
            if(is_null($has)){
                return jsonResponse([],'对应的父ID不存在!',404);
            }

            // 查看要挂接的下一级节点是不是和上级节点拥有相同的type
            if($has['type'] != $this->param['type']){
                return jsonResponse([],'type值不同的,不能进行挂接',400);
            }

            // 审批者必须针对个人
            $level = $this->param['level'];
            if($level != 1){
                return jsonResponse([],'审批者必选是员工ID',305);
            }

            //查看对的p_id是否已经存在下级节点
            $nextWhere[] = ['p_id','=',$p_id];
            $hasNext = $this->process
                ->where($nextWhere)
                ->find();

            if($hasNext){
                return jsonResponse([],'对应的p_id已经存在下级节点,不能再次添加',400);
            }

        }

        $target = $this->isLegal($this->param['level'],$this->param['target'],$personnel,$position,$department);
        if($target === false){
            return jsonResponse([],'所传入的target中没有合法值',405);
        }

        // 查找一条的记录中,有没有类型相同的,如果有,需要在以前的数据中删除那条记录
        if($p_id == 0){
            Db::startTrans();
            try{

                $this->deleteOld($this->param['type'],$this->param['level'],$target);
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                return jsonResponse([],'重复添加发起者更新老数据失败!',303);
            }
        }

        $this->param['target'] = $target;
        $result = $this->process->allowField('company_id,p_id,type,level,target')
                    ->isUpdate(false)->save($this->param);

        if(!$result){
            return jsonResponse([],'数据添加失败!',301);

        }
        return jsonResponse(['id'=>$this->process->id]);
    }


    /**
     * 查找流程规则表中是否以前有这些发起者的规则
     *
     * @param $type
     * @param $level
     * @param $targetArr
     * @param int $updateId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function deleteOld($type, $level, $targetArr, $updateId = 0){

        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['type','=',$type];
        $map[] = ['level','=',$level];
        $map[] = ['p_id','=','0'];

        if($updateId != 0){
            $map[] = ['id','<>',$updateId];
        }

        foreach ($targetArr as $item) {
            $has = $this->process
                    ->where($map)
                    ->where("JSON_CONTAINS(target, '".$item."','$')")
                    ->select();
            foreach ($has as $v) {
                $thisTarget = $v['target'];
                $newTarget = array_merge(array_diff($thisTarget,[$item]));

                if(empty($newTarget)){
                    // 如果没有了,那么删除该字段
                    $this->destroy($v['id']);
                }else{
                    $this->process->save(['target'=>$newTarget],['id'=>$v['id']]);
                }
            }
        }
    }

    /**
     * 筛选前端传入的ID是否是合法的
     *
     * @param $level
     * @param $targetArr
     * @param $personnel
     * @param $position
     * @param $department
     * @return array|bool
     */
    private function isLegal($level, $targetArr, $personnel, $position, $department){
        if($level == 1){
            // 针对个人
            $model = $personnel;
        }elseif($level == 2){
            $model = $position;
        }elseif($level == 3){
            $model = $department;
        }elseif($level == 4){
            return [$this->param['company_id']];
        }else{
            return false;
        }
        $newArr = [];

        foreach ($targetArr as $item) {
            $map['company_id'] = $this->param['company_id'];
            $map['id'] = $item;
            $has = $model->where($map)->find();
            if(!is_null($has)){
                $newArr[] = (int)$item;
            }
        }
        if(empty($newArr)){
            return false;
        }
        return $newArr;
    }

    /**
     * 删除一条规则
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function destroy($id){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $this->process->where($map)->find();
        if(is_null($has)){
            return jsonResponse([],'对应的规则不存在或者已删除!',404);
        }
        $this->process->startTrans();
        $result = $this->delOneRule($id);
        if($result){
            $this->process->commit();
            return jsonResponse();
        }
        $this->process->rollback();
        return jsonResponse([],'数据库写入失败',301);
    }

    /**
     * 递归删除一条规则
     *
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function delOneRule($id){
        $info = $this->process->get($id);
        if(is_null($info)){
            return true;
        }
        $result =  $this->process->destroy($id);

        if(!$result){
            return false;
        }

        $next = $this->process->where(['p_id'=>$id])->find();
        if(is_null($next)){
            return true;
        }
        return $this->delOneRule($next['id']);
    }
}
