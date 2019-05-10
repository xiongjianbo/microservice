<?php

namespace app\personnel\controller;

use app\personnel\model\TrainResource as TrainResourceModel;
use think\Request;
use app\personnel\model\TrainClass;

class TrainResource
{

    private $trainResource;

    private $param;

    /**
     * Train constructor.
     * @param $trainResource $train
     * @param Request $request
     */
    public function __construct(TrainResourceModel $trainResource, Request $request)
    {
        $this->trainResource = $trainResource;
        $this->param = $request->param();
    }


    /**
     * 获取课程的资源信息
     *
     * @param $id
     * @param TrainClass $model
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($id, TrainClass $model){

        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $model->where($map)->find();

        if(is_null($has)) {
            return jsonResponse([], '对应的课程不存在或者已删除', 404);
        }
        $hasInfo = $this->trainResource
            ->field('train_id,photo,video,file')

            ->get($has['id']);

        if(is_null($hasInfo)){
            return jsonResponse([],'对应的课程不存在或者已删除',404);
        }

        return jsonResponse($hasInfo);
    }


    /**
     * 修改培训资源
     *
     * @param $id
     * @param TrainClass $model
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update($id, TrainClass $model){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $hasInfo = $model->where($map)->find();

        if(is_null($hasInfo)) {
            return jsonResponse([], '对应的课程不存在或者已删除', 404);
        }

        $result =  $this->trainResource
            ->allowField(['photo','video','file'])
            ->save($this->param,['train_id'=>$id]);

        if($result){
            return jsonResponse();
        }

        return jsonResponse([],'操作失败',301);
    }
}
