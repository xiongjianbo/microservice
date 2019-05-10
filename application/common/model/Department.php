<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Department extends Model
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    private $departmentPIds;

    /**
     * 职位
     *
     * @return $this|\think\model\relation\hasMany
     */
    public function position()
    {
        return $this->hasMany('Position');
    }

    /**
     * 员工
     *
     * @return $this|\think\model\relation\hasMany
     */
    public function personnel()
    {
        return $this->hasMany('Personnel');
    }

    /**
     * 通过id获取所有子部门id数组
     *
     * @param $id
     * @param bool $withId
     * @return array
     */
    public function getChildrenIds($id, $withId = true)
    {
        if (is_array($id)) {
            $ids = $id;
        } else {
            $ids = [(int)$id];
        }

        $result = $withId ? $ids : [];

        $companyId = $this->where('id', $ids[0])->value('company_id');
        $this->departmentPIds = $this->where('company_id', $companyId)->column('p_id', 'id');

        $this->getChildren($ids, $result);
        return $result;
    }

    /**
     * 获取子id
     *
     * @param $pIds
     * @param $result
     */
    private function getChildren($pIds, &$result)
    {
        $cIds = [];
        foreach ($this->departmentPIds as $key => $item) {
            if (in_array($item, $pIds)) {
                $cIds [] = $key;
                $result [] = $key;
            }
        }
        if (!empty($cIds)) {
            $this->getChildren($cIds, $result);
        }
    }

}
