<?php

namespace app\personnel\controller;

use app\common\model\Comment;
use think\Request;
use app\personnel\model\TrainPersonnel as TrainPersonnelModel;
use app\personnel\model\TrainClass;

class TrainPersonnel
{
    private $trainPersonnel;

    private $param;

    /**
     * TrainPersonnel constructor.
     * @param TrainPersonnelModel $trainPersonnel
     * @param Request $request
     */
    public function __construct(TrainPersonnelModel $trainPersonnel, Request $request)
    {
        $this->trainPersonnel = $trainPersonnel;
        $this->param = $request->param();
    }

    /**
     * 查询某一个培训课程的报名人数信息
     *
     * @param $id
     * @param TrainClass $trainModel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($id, TrainClass $trainModel)
    {
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $trainModel->where($map)->find();

        if (is_null($has)) {
            return jsonResponse([], '该课程不存在或者已删除!', 404);
        }

        if($this->param['type'] == 'index'){
            $order = 'join_time';
        }else{
            $order = 'score';
        }

        $data = $this->trainPersonnel
            ->with([
                'personnelInfo' => function ($query) {
                    $query->withField('id,name,department_id')->with(['department' => function ($query) {
                        $query->withField('id,name');
                    }]);
                }
            ])
            ->field('personnel_id,score,join_time')
            ->where(['train_id' => $id])
            ->order($order, 'desc')
            ->select();

        return jsonResponse($data);
    }


    /**
     * 获取讲师评价、需求调查情况
     *
     * @param $id
     * @param TrainClass $trainClassModel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commentCensus($id, TrainClass $trainClassModel)
    {
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $trainClassModel->where($map)->find();

        if (is_null($has)) {
            return jsonResponse([], '该课程不存在或者已删除!', 404);
        }

        $teacherEvaluation = $this->trainPersonnel->getEvaluationPercent($id, 'teacher_evaluation');

        $evaluationResult = $this->calcEvaluation($teacherEvaluation->toArray(), true);

        $demandEvaluation = $this->trainPersonnel->getEvaluationPercent($id, 'demand_evaluation');

        $demandResult = $this->calcEvaluation($demandEvaluation->toArray(), false);

        $ar = [
            'teacher_evaluation' => $evaluationResult,
            'demand_evaluation' => $demandResult,
        ];

        return jsonResponse($ar);
    }


    /**
     * 提交培训课程的打分信息
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commentEdit($id)
    {
        // 判断员工是否已经评论过了
        $map['train_id'] = $id;
        $map['personnel_id'] = $this->param['userInfo']->id;
        $str = 'evaluation_time is null';
        $has = $this->trainPersonnel->where($map)->where($str)->find();
        if (is_null($has)) {
            return jsonResponse([], '打分已经提交或者没有打分资格', 404);
        }

        $info = [
            'teacher_evaluation' => $this->param['teacher_evaluation'],
            'demand_evaluation' => $this->param['demand_evaluation'],
            'evaluation_time' => date('Y-m-d H:i:s'),
        ];
        $result = $this->trainPersonnel->save($info, ['id' => $has['id']]);
        if ($result) {
            return jsonResponse();
        }
        return jsonResponse([], '数据库写入失败!', 301);
    }


    /**
     * 用户提交评论信息
     *
     * @param $id
     * @param Comment $comment
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commentAdd($id, Comment $comment)
    {
        // 查看有没有评论资格
        $personnelId = $this->param['userInfo']->id;
        // 查看员工是否报名
        $map['train_id'] = $id;
        $map['personnel_id'] = $personnelId;
        $has = $this->trainPersonnel->where($map)->find();
        if (is_null($has)) {
            return jsonResponse([], '未报名,不能进行评论!', 404);
        }

        $addInfo = [
            'content' => $this->param['evaluation'],
            'personnel_id' => $personnelId,
            'commentable_id' => $id,
            'commentable_type' => 'app\personnel\model\TrainClass',
        ];
        $result = $comment->save($addInfo);
        if ($result) {
            return jsonResponse();
        }
        return jsonResponse([], '数据库写入失败!', 301);
    }


    /**
     * 获取培训课程的评论列表
     *
     * @param $id
     * @param Comment $comment
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function commentList($id, Comment $comment)
    {
        $map['commentable_id'] = $id;
        $map['commentable_type'] = 'app\personnel\model\TrainClass';
        $data = $comment->where($map)
            ->order('create_time','desc')
            ->with(['personnelInfo'])
            ->visible(['id', 'content', 'create_time', 'personnel_info.name'])
            ->paginate($this->param['limit']);
        return jsonResponse($data);
    }

    /**
     * 计算讲师评价、需求调查百分比
     *
     * @param array $evaluation
     * @param bool $type true 讲师评价 false需求调查
     * @return array
     */
    private function calcEvaluation($evaluation, $type)
    {

        $total = 0;

        foreach ($evaluation as $item) {
            $total += $item['total'];
            $which[$item['evaluation']] = $item['total'];
        }

        if ($type) {
            $veryGood = '讲得非常好';
            $good = '讲得好';
            $normal = '一般';
        } else {
            $veryGood = '非常需要';
            $good = '需要';
            $normal = '不需要';
        }

        if ($total == 0) {
            $ar[] = [
                'type' => 1,
                'name' => $veryGood,
                'percent' => 0
            ];
            $ar[] = [
                'type' => 2,
                'name' => $good,
                'percent' => 0
            ];
            $ar[] = [
                'type' => 3,
                'name' => $normal,
                'percent' => 0
            ];
        } else {
            $ar[] = [
                'type' => 1,
                'name' => $veryGood,
                'percent' => (round(($which["1"] ?? 0) / $total, 4) * 100)
            ];
            $ar[] = [
                'type' => 2,
                'name' => $good,
                'percent' => (round(($which["2"] ?? 0) / $total, 4) * 100)
            ];
            $ar[] = [
                'type' => 3,
                'name' => $normal,
                'percent' => 100 - $ar[0]['percent'] - $ar[1]['percent']
            ];
        }

        return $ar;
    }

    /**
     * 判断是否有取消报名的权限
     *
     * @param int $personnelId 员工ID
     * @param int $joinType 1表示自愿参与,2表示强制参与,3表示指定人员强制+其他部分自愿
     * @param bool $isAll true表示全员,false表示指定人员
     * @param array $joinPersonnelArray 指定人员ID   [8002,8003]
     * @return bool
     */
    private function canJoin($personnelId, $joinType, $isAll, $joinPersonnelArray)
    {
        // 由于创建后不可修改,是强制参与的,系统已经自动报名,所以不能进行报名操作
        if ($isAll) {
            if ($joinType == 1) {
                return true;
            } else {
                return false;
            }
        }

        if ($joinType == 1) {
            // 自愿参与
            return in_array($personnelId, $joinPersonnelArray);
        } elseif ($joinType == 2) {
            // 强制参与
            return false;
        } else {
            // 指定强制，其他自愿
            return !in_array($personnelId, $joinPersonnelArray);
        }

    }


    /**
     * 修改培训考试结果
     *
     * @param $id
     * @param TrainClass $trainClass
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function score($id, TrainClass $trainClass)
    {

        $list = [];
        $result = true;

        // 判断是不是修改自己公司开设的培训课程
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $hasClass = $trainClass->where($map)->find();
        if(is_null($hasClass)){
            return jsonResponse([],'培训课程不存在或者已删除!',404);
        }

        foreach ($this->param['score_info'] as $item) {
            $has = $this->trainPersonnel->where([
                'train_id' => $id,
                'personnel_id' => $item['personnel_id']
            ])->find();

            if (is_null($has)) {
                continue;
            }
            $list[] = [
                'id' => $has['id'],
                'score' => $item['score'],
                'score_time' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($list)) {
            $result = $this->trainPersonnel->saveAll($list);
        }

        if ($result) {
            return jsonResponse();
        }
        return jsonResponse([], '操作失败!', 301);
    }

    /**
     * 报名参加，取消报名
     *
     * @param $id
     * @param TrainClass $trainClass
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function join($id, TrainClass $trainClass)
    {
        $personnelId = $this->param['userInfo']->id;
        $type = $this->param['userInfo']->type;

        if($type <= 0){
            return jsonResponse([],'该类型账号不能进行报名操作!',301);
        }

        // 判断是不是自己公司开设的培训课程
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $trainClass->where($map)->find();
        if(is_null($has)){
            return jsonResponse([],'培训课程不存在或者已删除!',404);
        }

        // 查询员工是否拥有成参加课程的权限
        $canJoin = $this->canJoin($personnelId, $has['join_type'], $has['target']['is_all'], $has['target']['personnel']);
        if (!$canJoin) {
            return jsonResponse([], 'join_type限制,不能进行该操作', 505);
        }

        $result = $this->trainPersonnel->joinClass($id, $personnelId, $this->param['state']);
        if ($result) {
            return jsonResponse();
        }
        return jsonResponse([], '操作失败', 301);
    }
}
