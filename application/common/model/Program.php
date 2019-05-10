<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Program extends Model
{
    use \auto\Check;

    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

    const TYPE_ORDER = 1;
    const TYPE_PROJECT = 2;
    const TYPE_TASK = 3;

    public function saveData($list, $sourceType, $sourceId, $companyId)
    {
        $delArr = [];
        foreach ($list as $key => &$item) {
            if(isset($item['is_delete']) && $item['is_delete'] == 1){
                $delArr[] = $item['id'];
                unset($list[$key]);
            }else {
                $item['source_type'] = $sourceType;
                $item['source_id'] = $sourceId;
                $item['company_id'] = $companyId;

                $moduleId = PresetProgram::getParentId($item['program_id'],4);
                $moduleId && $item['module_id'] = $moduleId;
                $categoryId = PresetProgram::getParentId($moduleId,3);
                $categoryId && $item['category_id'] = $categoryId;
                $platformId = PresetProgram::getParentId($categoryId,2);
                $platformId && $item['platform_id'] = $platformId;
            }
        }

        !empty($list) && self::saveAll($list);
        !empty($delArr) && self::destroy($delArr);
    }

    public static function getView($sourceId, $sourceType)
    {
        $list = self::where(['source_id' => $sourceId, 'source_type' => $sourceType])
            ->alias('p')
            ->leftJoin('preset_program pp','p.program_id=pp.id')
            ->leftJoin('preset_program ppm','p.module_id=ppm.id')
            ->leftJoin('preset_program ppc','p.category_id=ppc.id')
            ->leftJoin('preset_program ppp','p.platform_id=ppp.id')
            ->field([
                'p.id',
                'p.price',
                'p.company_id',
                'p.source_type',
                'p.source_id',
                'p.program_id',
                'pp.name',
                'p.module_id',
                'ppm.name as module_name',
                'p.category_id',
                'ppc.name as category_name',
                'p.platform_id',
                'ppp.name as platform_name'
            ])
            ->select()
            ->toArray();
        return $list;
    }
}
