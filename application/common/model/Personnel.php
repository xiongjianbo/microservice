<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Personnel extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    /**
     * 详细信息
     *
     * @return $this|\think\model\relation\hasOne
     */
    public function personnelInfo()
    {
        return $this->hasOne('PersonnelInfo')
            ->bind([
                'birthday',
                'education',
                'graduate_school',
                'job_description',
                'major',
                'home_place',
                'home_post',
                'address',
                'current_post',
                'contact',
                'contact_phone',
                'contact_role',
                'contact_address',
                'contact_post',
            ]);
    }

    /**
     * 职位
     *
     * @return $this|\think\model\relation\belongsTo
     */
    public function position()
    {
        return $this->belongsTo('Position');
    }

    /**
     * 部门
     *
     * @return $this|\think\model\relation\belongsTo
     */
    public function department()
    {
        return $this->belongsTo('Department');
    }

    /**
     * 所属公司
     *
     * @return \think\model\relation\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('Company');
    }
    /**
     * 反馈
     *
     * @return \think\model\relation\hasOne
     */
    public function feedbackInfo()
    {
        return $this->belongsTo('FeedbackInfo');
    }
    /**
     * 设置
     *
     * @return \think\model\relation\hasOne
     */
    public function PersonalSettings()
    {
        return $this->hasOne('PersonalSettings')
            ->bind([
                'is_welcome',
                'is_news',
                'is_task',
                'is_schedule',
                'is_open',
                'two_password',
                'personnel_id',
            ]);
    }
    /**
     * 获取指定部门下的员工
     *
     * @param $id
     * @param string $field
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDepartmentPersonnel($id, $field = 'id')
    {
        $departmentIds = (new Department())->getChildrenIds($id);
        $model = $this->whereIn('department_id', $departmentIds);
        if ($field === 'id') {
            $result = $model->column('id');
        } else {
            $result = $model->field($field)->select();
        }
        return $result;
    }
}
