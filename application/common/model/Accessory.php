<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Accessory extends Model
{
    use \auto\Check;

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = 'timestamp';
    protected $updateTime = 'update_time';

    const TYPE_ORDER = 1;
    const TYPE_PROJECT = 2;
    const TYPE_TASK = 3;
    const TYPE_DELIVERY=4;//项目交付

    /**
     * 获取显示数据
     */
    public static function getView($sourceId,$sourceType){
        $list = self::where(['source_id' => $sourceId,'source_type' => $sourceType])
            ->field('id,source_type,source_id,file_id')
            ->select()
            ->toArray();
        foreach ($list as &$item){
            $item['url'] = File::getUrl($item['file_id']);
        }
        return $list;
    }
    public function getFileResourceName($num)
    {
        $ret = '';
        switch ($num) {
            case 1:
                $ret = 'order';
                break;
            case 2:
                $ret = 'project';
                break;
            case 3:
                $ret = 'task';
                break;
            case 4:
                $ret='project_delivery';
                break;
            case 5:
                $ret='task_submit';
                break;
            default:
                break;
        }
        return $ret;
    }

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

                /**更新file表**/
                $fileModel = new File();
                $fileResource = self::getFileResourceName($sourceType);
                $fileModel->updateResource($item['file_id'], $fileResource, $sourceId);
            }
        }

        !empty($list) && self::saveAll($list);
        !empty($delArr) && self::destroy($delArr);
    }
}
