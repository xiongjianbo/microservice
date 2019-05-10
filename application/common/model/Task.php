<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use \think\db\exception\DataNotFoundException;
use \think\db\exception\ModelNotFoundException;
use \think\exception\DbException;

class Task extends Model
{
    use \auto\Check;
    public static $lastTaskNumber;

    //use SoftDelete;
    //protected $deleteTime = 'delete_time';
    //反馈信息
    public function feedback()
    {
        return $this->hasMany('FeedBack');
    }

    public function getIdList($array = [])
    {
        is_object($array) && $array = $array->toArray();
        return array_column($array, 'id');
    }

    public function getSubTask($data)
    {
        $newData = $data['data'] ?? [];
        $id_list = array_column($newData, 'id');
        $id_list = implode(',', $id_list);
        $sub = $this
            ->whereIn('task.p_id', $id_list)
            ->field([
                'task.*',
                'projects.name as projects_name',
                'company.name as company_name',
                'personnel.name as personnel_name',
                'skill_category.name as skill_category_name',
            ])
            ->leftJoin('projects', 'projects.id = task.project_id')
            ->leftJoin('company', 'company.id = task.company_id')
            ->leftJoin('personnel', 'personnel.id = task.personnel_id')
            ->leftJoin('skill_category', 'task.type = skill_category.id')
            ->select();
        $sub = $sub->isEmpty() ? [] : $sub->toArray();

        $newSub = [];
        foreach ($sub as $row) {
            $newSub[$row['p_id']][] = $row;
        }
        foreach ($newData as &$vo) {
            $vo['children'] = $newSub[$vo['id']] ?? [];
        }
        $data['data'] = $newData;
        return $data;
    }

    /**
     * 按照顺序生成公司编号
     * @param int $company_id
     * @return bool|int|string
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getTaskNumber($company_id = 0)
    {
        $find_rule = 'RW' . date('Ymd');

        if (isset(self::$lastTaskNumber) && !empty(self::$lastTaskNumber)) {
            $last_task_number = substr(self::$lastTaskNumber, -4);
            $last_task_number = (int)$last_task_number + 1;
            $last_task_number = sprintf('%04s', $last_task_number);
            self::$lastTaskNumber = $find_rule . $company_id . $last_task_number;
            return self::$lastTaskNumber;
        }

        $data = self::field('task_number')
            ->order('id', 'desc')
            ->whereLike('task_number', "%{$find_rule}%")
            ->find();
        if (!$data) {
            $last_task_number = $find_rule . $company_id . '0001';
        } else {
            $data = $data->toArray();
            $task_number = current($data);
            $last_task_number = substr($task_number, -4);
            $last_task_number = (int)$last_task_number + 1;
            $last_task_number = sprintf('%04s', $last_task_number);
            $last_task_number = $find_rule . $company_id . $last_task_number;
        }
        self::$lastTaskNumber = $last_task_number;
        /** @var TYPE_NAME $last_task_number */
        return $last_task_number;
    }
}
