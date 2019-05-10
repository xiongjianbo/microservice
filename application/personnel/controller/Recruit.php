<?php

namespace app\personnel\controller;

use think\Request;
use app\personnel\model\RecruitInner as RecruitModel;

class Recruit
{

    private $inner;

    private $param;

    public function __construct(RecruitModel $inner, Request $request)
    {
        $this->inner = $inner;
        $this->param = $request->param();
    }

    /**
     * 获取内推列表
     *
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function innerIndex(){
        $map[] = ['company_id','=',$this->param['company_id']];
        $name = $this->param['name'] ?? '';
        $map[] = ['name|phone','like','%'.$name.'%'];

        if(isset($this->param['status'])){
            $map[] = ['current_status','=',$this->param['status']];
        }

        if(isset($this->param['personnel_id'])){
            $map[] = ['personnel_id','=',$this->param['personnel_id']];
        }
        $data = $this->inner
            ->with([
                'personnelInfo' => function ($query) {
                    $query->withField('id,name,department_id')->with(['department' => function ($query) {
                        $query->withField('id,name');
                    }]);
                }
            ])
            ->field('id,name,phone,resume_uri,create_time,personnel_id')
            ->where($map)
            ->order('create_time','desc')
            ->paginate($this->param['limit']);

        return jsonResponse($data);
    }

    /**
     * 新增内推人员
     *
     * @return \think\response\Json
     */
    public function innerStore(){
        $this->param['personnel_id'] = $this->param['userInfo']->id;
        $this->param['current_status'] = 1;
        $result = $this->inner->allowField('phone,name,resume_uri,personnel_id,company_id')->save($this->param);
        if($result){
            return jsonResponse();
        }
        return jsonResponse([],'数据库写入失败!',301);
    }

    /**
     * 修改内推状态
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function innerUpdateStatus($id){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $this->inner->where($map)->find();

        if(is_null($has)){
            return jsonResponse([],'该内推人员不存在或已删除!',404);
        }

        $currentStatus = $this->param['status'];
        $nowStatus = $has['current_status'];

        if($nowStatus == 1){
            if($currentStatus != 2){
                return jsonResponse([],'已推荐状态只能变更为面试已结束',301);
            }
        }

        if($nowStatus == 2){
            if($currentStatus <= 2){
                return jsonResponse([],'面试已结束只能修改为录用或未录用',301);
            }
        }

        if($nowStatus > 2){
            return jsonResponse([],'录用或未录用状态不能进行修改',301);
        }

        $result = $this->inner->save(['current_status'=>$currentStatus],['id'=>$id]);

        if($result){
            return jsonResponse();
        }
        return jsonResponse([],'数据库写入失败!',301);
    }


    /**
     * 删除内推人员
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function innerDestroy($id){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $this->inner->where($map)->find();

        if(is_null($has)){
            return jsonResponse([],'该内推人员不存在或已删除!',404);
        }

        $result = $this->inner->destroy($has['id']);
        if($result){
            return jsonResponse();
        }

        return jsonResponse([],'数据库写入失败!',301);
    }
}
