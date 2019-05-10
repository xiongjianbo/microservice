<?php

namespace app\common\model;

use think\Db;
use think\Model;
use think\Request;

class ProjectDelivery extends Model
{
    use \auto\Check;

    const STATUS_WAIT=0;
    const STATUS_REFUSE=1;
    const STATUS_PASS=2;

    protected $autoWriteTimestamp = 'timestamp';
    protected $insert = ['number','status' =>0,'commit_date'];
    protected $readonly = ['number'];


    //新增时自动生成编号
    protected function setNumberAttr()
    {
        $str = "JF";
        $dateStr = date('YmdHis');
        $randStr = rand(1000,9999);
        return $str.$dateStr.$randStr;
    }

    //新增时自动生成提交日期
    protected function setCommitDateAttr(){
        return time();
    }

    public function projects()
    {

        return $this->belongsTo('Projects','project_id');
    }


    //获取列表
    public function getList(Request $request)
    {
        $listRows = $request->get('per_page', config('page.listRows'));
        $page = $request->get('page', 1);
        $keywords = $request->get('keywords');
        $status = $request->get('status');
        $model = $this->alias('a')
            ->leftJoin('projects b', 'a.project_id=b.id')
            ->leftJoin('personnel c', 'b.personnel_id=c.id')
            ->leftJoin('orders d', 'b.order_id=d.id')
            ->leftJoin('project_type e', 'b.project_type_id=e.id');
        //关键字查询
        if (!empty($keywords)) {
            $model->where('b.name|c.name', 'like', '%' . $keywords . '%');
        }
        //状态查询
        if (isset($status)) {
            $model->where('a.status', $status);
        }
        return $model->field("a.id,
                              a.project_id,
                              a.number as delivery_number,
                              b.number as project_number,
                              d.number as order_number, 
                              b.name as project_name,
                              c.name as personnel_name, 
                              e.name as project_type_name, 
                              b.expect_date, 
                              a.commit_date,
                              b.status as project_status,
                              a.status as delivery_status,
                              a.is_collection,
                              comment")
            ->paginate($listRows, false, [
                'page' => $page
            ])
            ->toArray();
    }

    //获取指定
    public function getById($id)
    {
        $data= $this->alias('a')
            ->leftJoin('projects b', 'a.project_id=b.id')
            ->leftJoin('personnel c', 'b.personnel_id=c.id')
            ->leftJoin('orders d', 'b.order_id=d.id')
            ->leftJoin('project_type e', 'b.project_type_id=e.id')
            ->field("a.id,
                     a.project_id,
                     a.number as delivery_number,
                     b.number as project_number,
                     d.number as order_number, 
                     b.name as project_name,
                     c.name as personnel_name, 
                     e.name as project_type_name, 
                     b.expect_date, 
                     b.status as project_status,
                     b.expect_date,
                     b.done_date,
                     b.begin_date,
                     a.commit_date,
                     a.status as delivery_status,
                     a.is_collection,
                     a.comment,
                     a.audit_comment")
            ->findOrEmpty($id)
            ->toArray();

        if(!empty($data)){
            $data['accessory_list'] = Accessory::getView($id,Accessory::TYPE_DELIVERY);
        }
        return $data;
    }


}
