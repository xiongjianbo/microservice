<?php

namespace app\personnel\controller;

use think\Db;
use think\Request;
use app\personnel\model\SystemNotice;
use app\personnel\model\SystemNoticePersonnel;
use app\common\model\Personnel;

class Notice
{
    private $notice;

    private $param;

    /**
     * Train constructor.
     * @param SystemNotice $notice
     * @param Request $request
     */
    public function __construct(SystemNotice $notice, Request $request)
    {
        $this->notice = $notice;
        $this->param = $request->param();
    }

    /**
     * 获取已读,未读列表信息
     *
     * @param $id
     * @param SystemNoticePersonnel $noticePersonnel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function readDetail($id , SystemNoticePersonnel $noticePersonnel){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $this->notice->where($map)->find();

        if(is_null($has)){
            return jsonResponse([],'该公告不存在或者已删除!',404);
        }

        $where['system_notice_id'] = $id;
        $where['is_read'] = $this->param['is_read'];
        $data = $noticePersonnel
            ->where($where)
            ->field('system_notice_id,personnel_id,is_read,read_time')
            ->with(['personnelInfo'=>function($query){
                $query->field('id,name,department_id')->with(['department'=>function($query){
                    $query->field('id,name');
                }]);
            }])
            ->order("read_time",'desc')
            ->paginate($this->param['limit']);

        return jsonResponse($data);
    }
    /**
     * 获取公告列表
     *
     * @param SystemNoticePersonnel $noticePersonnel
     * @param Personnel $personnel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(SystemNoticePersonnel $noticePersonnel, Personnel $personnel){
        $departmentId = $this->param['userInfo']->department_id;
        $personnelId = $this->param['userInfo']->id;

        $map[] = ['company_id','=',$this->param['company_id']];

        // 公告内容查询
        if(isset($this->param['name'])){
            $map[] = ['title','like','%'.$this->param['name'].'%'];
        }

        // 发布者查询   这里涉及权限问题,可能不能进行该筛选
        if(isset($this->param['personnel_id'])){
            $map[] = ['personnel_id','=',$this->param['personnel_id']];
        }

        // 接受员工筛选
        $personnelIdArr = null;
        $powerPersonnelIdArr = null;

        // 接受员工ID查询,前端输入
        if(isset($this->param['join_personnel_id'])){
            $thisWhere['personnel_id'] = $this->param['join_personnel_id'];
            $personnelIdArr = $noticePersonnel->where($thisWhere)->column('system_notice_id');
        }

        // 这里有可能是超级管理员
        $userType = $this->param['userInfo']->type;
        if($userType == 0){
            // 超级管理员
            $power = 1;
        }else{
            $powerArr = json_decode($this->param['userInfo']->position->rules,true);
            $power = $powerArr['personnel-Notice-index'];
        }

        // 不是查看所有,需要筛选
        if($power != 1){
            // 获取该部门下的所有员工ID
            $idArr = [];

            // 1所有,2部门,3自己
            if($power == 2){
                $idArr = $personnel->getDepartmentPersonnel($departmentId);
            }

            if($power == 3){
                $idArr =[$personnelId];
            }
            $powerWhere[] = ['personnel_id','in',$idArr];
            // 能看的权限
            $powerPersonnelIdArr = $noticePersonnel->where($powerWhere)->column('system_notice_id');

        }

        // 没有进行权限筛选
        if(is_null($powerPersonnelIdArr)){
            $personnelIn = $personnelIdArr;
        }else{

            if(is_null($personnelIdArr)){
                // 前端没有对接受员工进行筛选
                $personnelIn = $powerPersonnelIdArr;
            }else{
                $personnelIn = array_intersect($personnelIdArr,$powerPersonnelIdArr);
            }
        }

        if(!is_null($personnelIn)){
            $map[] = ['id','in',$personnelIn];
        }

        $info = $this->notice
            ->where($map)
            ->field("id,is_all,personnel_id,title,begin_time,create_time")
            ->with([ 'personnelInfo'=>function($query){
                $query->field('id,name');
            }])
            ->withCount([
                'noticePersonnelRead'=>function($query){
                    $query->where("is_read",1);
                },
                'noticePersonnel'=>function($query){
                    $query->where("is_read",0);
                }
            ])->order('create_time','desc')
            ->paginate($this->param['limit']);

        return jsonResponse($info);
    }

    /**
     * 获取公告详情
     *
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function show($id){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];
        $has = $this->notice
            ->field('id,is_all,personnel_id,title,begin_time,content,create_time')
            ->with([
                'personnelInfo'=>function($query){
                    $query->field('id,name');
                },
                'noticePersonnel'=>function($query){
                    $query->field('system_notice_id,personnel_id')->with(['personnelInfo'=>function($query){
                        $query->field('id,name');
                    }]);
                }
            ])
            ->where($map)
            ->find();

        if(is_null($has)){
            return jsonResponse([],'对应的公告不存在或者已删除!',404);
        }
        return jsonResponse($has);
    }

    /**
     * 标记公告未已读状态
     *
     * @param $id   公告ID
     * @param SystemNoticePersonnel $noticePersonnelModel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function read($id, SystemNoticePersonnel $noticePersonnelModel){
        $map['system_notice_id'] = $id;
        $map['personnel_id'] = $this->param['userInfo']->id;

        $has = $noticePersonnelModel->where($map)->find();
        if(is_null($has)){
            return jsonResponse([],'该消息不存在或者已删除!',404);
        }

        if($has['is_read'] == 1){
            return jsonResponse([],'已经设置为已读状态!',302);
        }

        $arr = [
            'is_read'   => 1,
            'read_time'   => date('Y-m-d H:i:s'),
        ];

        $result = $noticePersonnelModel->save($arr,['id'=>$has['id']]);
        if($result){
            return jsonResponse();
        }
        return jsonResponse([],'数据库写入失败!',301);
    }

    /**
     * 删除一条系统公告
     *
     * @param $id
     * @param SystemNoticePersonnel $noticePersonnelModel
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function destroy($id, SystemNoticePersonnel $noticePersonnelModel){
        $map[] = ['company_id','=',$this->param['company_id']];
        $map[] = ['id','=',$id];

        $has = $this->notice->where($map)->with(['noticePersonnel'])->find();

        if(is_null($has)){
            return jsonResponse([],'对应的公告不存在或者已删除!',404);
        }

        Db::startTrans();
        try{

            $this->notice->destroy($has['id']);

            $noticePersonnelModel->destroy(function($query)use($id){
                $query->where(['system_notice_id'=>$id]);
            });

            Db::commit();
            return jsonResponse();
        }catch (\Exception $e){

            Db::rollback();
            return jsonResponse([],'数据库操作失败!',301);
        }
    }

    /**
     * 新增一个系统公告
     *
     * @param Personnel $personnelModel
     * @return \think\response\Json
     */
    public function store(Personnel $personnelModel){
        $this->param['personnel_id'] = $this->param['userInfo']->id;
        Db::startTrans();
        try{
            $this->notice
                ->allowField('is_all,company_id,personnel_id,title,begin_time,content')
                ->save($this->param);
            $arr = [];
            if($this->param['is_all']){
                $map[] = ['company_id','=',$this->param['company_id']];
                $map[] = ['type','>',0];
                $ids = $personnelModel->where($map)->column('id');

                foreach ($ids as $data) {
                    $arr[] = [
                        'personnel_id' => $data
                    ];
                }
            }else{
                foreach ($this->param['personnel'] as $item) {
                    // 判断是不是该公司的员工
                    $has = $personnelModel->get($item);
                    if(is_null($has) || $has['company_id'] != $this->param['company_id'] || $has['type'] <= 0){
                        continue;
                    }
                    $arr[] = [
                        'personnel_id' => $item
                    ];
                }
            }

            if(count($arr) == 0){
                Db::rollback();
                return jsonResponse([],'不是全员的,必选选择接受对象',301);
            }
            $this->notice->noticePersonnel()->saveAll($arr);
            Db::commit();
            return jsonResponse();
        }catch (\Exception $e){
            Db::rollback();
            return jsonResponse([],'数据库操作失败!',301);
        }
    }
}
