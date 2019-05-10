<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class OrderPay extends Model
{
    use \auto\Check;
    //use SoftDelete;
    //protected $deleteTime = 'delete_time';

    public function saveData($list, $orderId, $companyId)
    {
        $delArr = [];
        foreach ($list as $key => &$item) {
            if(isset($item['is_delete']) && $item['is_delete'] == 1){
                $delArr[] = $item['id'];
                unset($list[$key]);
            }else {
                $item['order_id'] = $orderId;
                $item['company_id'] = $companyId;
            }
        }

        self::saveAll($list);
        self::destroy($delArr);
    }
}
