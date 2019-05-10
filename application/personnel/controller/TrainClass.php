<?php

namespace app\personnel\controller;

use think\Request;
use app\personnel\model\TrainClass as TrainModel;
use app\personnel\model\TrainPersonnel;
use app\personnel\model\TrainResource;
use app\common\model\Personnel;

class TrainClass
{

    private $train;

    private $param;

    /**
     * Train constructor.
     * @param TrainModel $train
     * @param Request $request
     */
    public function __construct(TrainModel $train, Request $request)
    {
        $this->train = $train;
        $this->param = $request->param();
    }


    /**
     * 获取培训课程列表
     *
     * @param Personnel $personnel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Personnel $personnel)
    {
        $departmentId = $this->param['userInfo']->department_id;
        $personnelId = $this->param['userInfo']->id;

        $power = $this->param['ruleAuth'];

        $map[] = ['company_id', '=', $this->param['company_id']];
        $field = 'id,name,begin_time,teacher,address,target,join_type,current_status';

        if (isset($this->param['name'])) {
            $map[] = ['name', 'like', '%' . $this->param['name'] . '%'];
        }
        if (isset($this->param['teacher'])) {
            $map[] = ['teacher', 'like', '%' . $this->param['teacher'] . '%'];
        }

        $classArr = $searchPersonnelArr = null;

        // 如果不可以查看所有人的,那么进行筛选
        if ($power != 1) {
            // 获取该部门下的所有员工ID
            $idArr = [];

            // 1所有,2部门,3自己
            if ($power == 2) {
                $idArr = $personnel->getDepartmentPersonnel($departmentId);
            }
            if ($power == 3) {
                $idArr = [$personnelId];
            }

            $firstClassData = $this->train->field('id,target')->where($map)->select()->toArray();


            foreach ($firstClassData as $item) {
                if ($item['target']['is_all']) {
                    $classArr[] = $item['id'];
                    continue;
                }
                $common = array_intersect($item['target']['personnel'], $idArr);
                if (count($common)) {
                    $classArr[] = $item['id'];
                    continue;
                }
            }
        }

        // 需要筛选员工
        if (isset($this->param['personnel_id'])) {
            $searchPersonnelArr = $this->train
                ->where("JSON_CONTAINS(target->'$.personnel','" . $this->param['personnel_id'] . "') or target->'$.is_all' = true")
                ->column('id');
        }

        // 没有进行权限筛选
        if (is_null($classArr)) {
            $personnelIn = $searchPersonnelArr;
        } else {

            if (is_null($searchPersonnelArr)) {
                // 前端没有对接受员工进行筛选
                $personnelIn = $classArr;
            } else {
                $personnelIn = array_intersect($classArr, $searchPersonnelArr);
            }
        }

        if (!is_null($personnelIn)) {
            $map[] = ['id', 'in', $personnelIn];
        }

        $classData = $this->train
            ->field($field)
            ->where($map)
            ->order('create_time', 'desc')
            ->paginate($this->param['limit']);

        return jsonResponse($classData);
    }


    /**
     * 获取培训详细信息
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception\DbException
     */
    public function show($id)
    {
        $personnelId = $this->param['userInfo']->id;
        $data = $this->train->field("delete_time,company_id", true)
            ->with(['joinedPersonnel' => function ($query) use ($personnelId) {
                $query->field('join_time,personnel_id,train_id,evaluation_time')->where('personnel_id',$personnelId);
            }])
            ->get($id);
        if (!$data) {
            return jsonResponse([], '对应的课程不存在或者已删除', 404);
        }
        return jsonResponse($data);
    }


    /**
     * 删除一个培训课程
     *
     * @param $id  课程ID
     * @param TrainPersonnel $trainPersonnelModel
     * @param TrainResource $resourceModel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function destroy($id, TrainPersonnel $trainPersonnelModel, TrainResource $resourceModel)
    {

        $map[] = ['company_id', '=', $this->param['company_id']];
        $map[] = ['id', '=', $id];
        $has = $this->train->where($map)->find();
        if (is_null($has)) {
            return jsonResponse([], '对应的培训课程不存在或者已删除!', 404);
        }

        $this->train->startTrans();

        $result = $this->train->destroy($id);


        $resultPersonnel = $trainPersonnelModel->destroy(function ($query) use ($id) {
            $query->where(['train_id' => $id]);
        });

        $resultResource = $resourceModel->destroy($id);

        if ($result && $resultPersonnel && $resultResource) {

            $this->train->commit();
            return jsonResponse();
        }

        $this->train->rollback();
        return jsonResponse([], '数据库事务操作失败!', 301);
    }


    /**
     * 修改培训课程
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function update($id)
    {
        $info = $this->param;
        return $this->add($info, $id);
    }


    /**
     * string 数组转int
     *
     * @param $array
     * @return array
     */
    private function intArrayToStringArray($array)
    {
        $arr = [];
        foreach ($array as $item) {
            $arr[] = $item + 0;
        }
        return $arr;
    }

    /**
     * 新增或者修改一条培训记录信息
     *
     * @param $info
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    private function add($info, $id = 0)
    {

        $target['personnel'] = [];

        // 判断ID是否存在
        if ($id != 0) {
            $where[] = ['company_id', '=', $this->param['company_id']];
            $where[] = ['id', '=', $id];
            $has = $this->train->where($where)->find();
            if (is_null($has)) {
                return jsonResponse([], '指定培训课程不存在!', 404);
            }
        } else {
            if ($info['is_all']) {
                $target['is_all'] = true;
            } else {
                $target['is_all'] = false;
                if (isset($info['target'])) {
                    $target['personnel'] = $this->intArrayToStringArray($info['target']);
                } else {
                    $target['personnel'] = [];
                }
            }
        }

        $info['target'] = $target;
        $this->train->startTrans();

        if ($id == 0) {

            $result = $this->train->allowField(true)
                ->together([
                    'classResource' => [
                        'photo' => json_encode([]),
                        'video' => json_encode([]),
                        'file' => json_encode([]),
                    ],
                ])
                ->save($info);
            // 强制 指定人员强制
            $joinType = $info['join_type'];
            if ($joinType == 2 || $joinType == 3) {
                $personnelModel = new Personnel();
                $map[] = ['company_id', '=', $this->param['company_id']];

                $map[] = ['type', '>', 0];

                // 是不是选择的全员
                if ($info['is_all']) {
                    // 获取该公司的所有员工ID
                    $data = $personnelModel->where($map)->column('id');

                    foreach ($data as $item) {
                        $ar[] = [
                            'personnel_id' => $item,
                            'join_time' => date('Y-m-d H:i:s')
                        ];
                    }
                } else {
                    foreach ($target['personnel'] as $item) {
                        // 校验是否是合法的员工ID
                        $where['id'] = $item;
                        $has = $personnelModel->where($where)->where($map)->find();

                        if (is_null($has)) {
                            continue;
                        }
                        $ar[] = [
                            'personnel_id' => $item,
                            'join_time' => date('Y-m-d H:i:s')
                        ];
                    }

                }

                if (!empty($ar)) {
                    $joinResult = $this->train->joinedPersonnel()->saveAll($ar);
                } else {
                    $joinResult = true;
                }

            } else {
                $joinResult = true;
            }

        } else {
            $result = $this->train->allowField(true)->save($info, ['id' => $id]);
            $joinResult = true;
        }


        if ($result && $joinResult) {
            $this->train->commit();
            return jsonResponse();
        }

        $this->train->rollback();
        return jsonResponse([], '操作失败', 301);
    }


    /**
     * 新增一个培训课程
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function store()
    {
        $info = $this->param;
        return $this->add($info);
    }
}
