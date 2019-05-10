<?php

namespace app\personnel\controller;

use app\common\model\ProcessStep;
use app\common\service\Process;
use think\Db;
use think\Request;
use app\common\model\Personnel;

class SupplementPurchase
{

    private $param;

    private $purchase;

    private $process;

    public function __construct(Request $request, Process $process, \app\asset\model\SupplementPurchase $purchase)
    {
        $this->param = $request->param();
        $this->process = $process;
        $this->purchase = $purchase;
    }

    /**
     * 获取采购以及审批的详细信息
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception\DbException
     */
    public function show($id)
    {
        $data = $this->purchase
            ->field("id,personnel_id,type,day,sign_time,reason,attachment_uri,current_status,create_time,update_time")
            ->with(['personnelInfo' => function ($query) {
                $query->field("id,name,department_id")->with(['department' => function ($query) {
                    $query->field("id,name");
                }]);
            }, 'stepInfo' => function ($query) {
                $query->order('id', 'asc')
                    ->where('supplement_type', 'app\personnel\model\SupplementSign')
                    ->field("verify_status,verify_time,verify_personnel_id,supplement_id,verify_content")
                    ->with(['personnelInfo' => function ($query) {
                        $query->field("id,name,department_id")->with(['department' => function ($query) {
                            $query->field("id,name");
                        }]);
                    }]);
            }])
            ->get($id);
        return jsonResponse($data);
    }

    /**
     * 采购审批
     *
     * @param $id int ProcessStep表中的主键ID
     * @return \think\response\Json
     */
    public function update($id)
    {
        $userInfo = $this->param['userInfo'];
        Db::startTrans();
        try {
            $result = $this->process->gotoNextStep($id, $this->param['verify_status'], $userInfo, $this->param['verify_content']);

            Db::commit();
            if ($result['code'] > 300) {
                return jsonResponse([], $result['message'], $result['code']);
            }
            return jsonResponse([], $result['message']);
        } catch (\Exception $e) {
            Db::rollback();
            return jsonResponse([], $e->getMessage(), 301);
        }
    }

    /**
     * 获取待审批列表
     *
     * @param ProcessStep $stepModel
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function wait(ProcessStep $stepModel)
    {
        $current_status = $this->param['current_status'];

        $where[] = ['verify_status', '=', $current_status];
        $where[] = ['supplement_type', '=', 'app\personnel\model\SupplementSign'];
        $whereString = "JSON_CONTAINS(verify_target,'" . $this->param['userInfo']->id . "','$')";

        $data = $stepModel->where($where)
            ->field("id,supplement_id,verify_status,verify_personnel_id")
            ->order('create_time', 'desc')
            ->where($whereString)
            ->with(['signInfo' => function ($query) {
                $query->field("id,personnel_id,day,sign_time,create_time,type")
                    ->with(['personnelInfo' => function ($query) {
                        $query->field("id,name,department_id")->with(['department' => function ($query) {
                            $query->field('id,name');
                        }]);
                    }]);
            }, 'personnelInfo' => function ($query) {
                $query->field("id,name,department_id")->with(['department' => function ($query) {
                    $query->field('id,name');
                }]);
            }])
            ->paginate($this->param['limit']);


        return jsonResponse($data);
    }

    /**
     * 获取采购列表
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

        // 查看范围:1-所有;2-部门;3-自己
        switch ($this->param['ruleAuth']) {
            case 1:
                $classArr = $personnel->where('company_id', '=', $this->param['company_id'])->column('id');
                break;
            case 2:
                $classArr = $personnel->getDepartmentPersonnel($departmentId);
                break;
            case 3:
                $classArr = [$personnelId];
                break;
        }

        // 需要筛选部门
        if (isset($this->param['department_id'])) {
            $departmentArr = $personnel->getDepartmentPersonnel($this->param['department_id']);
            $classArr = array_intersect($departmentArr, $classArr);
        }

        // 需要筛选员工
        if (isset($this->param['personnel_id'])) {
            if (!in_array($this->param['personnel_id'], $classArr)) {
                return jsonResponse([], '不能查看指定员工的采购信息,权限不足!', 405);
            }
            $classArr = $this->param['personnel_id'];
        }

        $map[] = ['personnel_id', 'in', $classArr];

        $field = 'id,personnel_id,type,day,sign_time,reason,attachment_uri,create_time,update_time,current_status,create_time';

        $data = $this->purchase
            ->field($field)
            ->with([
                'personnelInfo' => function ($query) {
                    $query->withField('id,name,department_id')->with(['department' => function ($query) {
                        $query->withField('id,name');
                    }]);
                }
            ])->order('create_time', 'desc')
            ->where($map)->paginate($this->param['limit']);

        return jsonResponse($data);
    }

    /**
     * 提交采购申请
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function store()
    {
        $this->param['personnel_id'] = $this->param['userInfo']->id;

        $getNextStep = $this->process->findNextStep($this->param['userInfo'], 3);

        if ($getNextStep === false) {
            return jsonResponse([], '系统没有对应的规则,不能提交该申请!', 404);
        }

        if ($getNextStep === null) {
            // 没有下一步
            return jsonResponse([], '系统没有对应审批者,请联系管理员新增', 405);
        }

        $this->purchase->startTrans();

        $result = $this->purchase
            ->allowField('personnel_id,name,parameter,number,unit,price,price_unit,supplier_id,reason')
            ->save($this->param);
        $stepResult = $this->process
            ->insertStep($this->purchase->id, 'app\asset\model\SupplementPurchase',
                $getNextStep['nextRuleId'], $getNextStep['nextPersonnel']);
        if ($result && $stepResult) {
            $this->purchase->commit();
            return jsonResponse();
        }
        $this->purchase->rollback();
        return jsonResponse([], '数据库写入失败!', 301);
    }
}
