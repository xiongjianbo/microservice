<?php

use think\migration\Migrator;

class orderPay extends Migrator
{
    public function change()
    {
        $this->table('order_pay')
            ->setComment('订单结算表')
            ->addColumn('company_id', 'integer', ['limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('type', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'type 结算类型：1：预付款；2：定时分期付款；3：项目验收后付款；4：按子项目分期付款'])
            ->addColumn('order_id', 'integer', ['limit' => 11, 'comment' => 'order_id 订单ID'])
            ->addColumn('expect_data', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'expect_data 预付款到账日期'])
            ->addColumn('expect_money', 'decimal', ['null' => true, 'precision' => 32, 'scale' => 4, 'comment' => 'expect_money 预付款金额'])
            ->addColumn('currency_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'currency_id 所属货币ID'])
            ->addColumn('status', 'integer',
                ['null' => true, 'limit' => 11, 'default' => '0', 'comment' => 'status 状态：0：待支付；1已支付'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}