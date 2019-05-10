<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class File extends Model
{
    use \auto\Check;

    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

    public static function getUrl($id){
        $info = self::find($id);
        $domain = FileRouter::where(['id'=> $info['router_id']])->value('domain');
        return "http://" . $domain . '/' . $info['name'];
    }
    public function updateResource($id, $resource = '', $resourceId = '')
    {
        $info = self::find($id);
//        /**判断文件是否写入数据库**/
//        if (!$info) {
//            exception(lang('NO_DATA_FILE'));
//        }
//        /**判断文件是否被占用**/
//        if ($info['resource'] != null || $info['resource_id'] != null) {
//            /**修改时不抛出异常**/
//            $info['resource_id'] !== $resourceId && exception(lang('FILE_USED'));
//        }

        /**更新resource**/
        $data = [
            'resource' => $resource,
            'resource_id' => $resourceId
        ];
        self::save($data, ['id' => $id]);
    }
}
