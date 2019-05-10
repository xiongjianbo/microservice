<?php

use think\migration\Migrator;

class orders extends Migrator
{
    public function change()
    {
        $this->table('orders')
            ->setComment('订单表')
            ->addColumn('number', 'string', ['limit' => 32, 'comment' => 'number 订单编号'])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('customer_id', 'integer', ['limit' => 11, 'comment' => 'customer_id 客户ID'])
            ->addColumn('project_type_id', 'integer', ['limit' => 11, 'comment' => 'project_type_id 项目类别ID'])
            ->addColumn('name', 'string', ['limit' => 32, 'comment' => 'name 项目名称'])
            ->addColumn('expect_date', 'integer', ['limit' => 11, 'comment' => 'expect_date 预计交付日期'])
            ->addColumn('expect_money', 'decimal', ['precision' => 32, 'scale' => 4, 'comment' => 'expect_money 预算'])
            ->addColumn('currency_id', 'integer', ['limit' => 11, 'comment' => 'currency_id 所属货币ID'])
            ->addColumn('personnel_id', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'personnel_id 所属业务人员ID'])
            ->addColumn('pay_type', 'integer',
                ['limit' => 11, 'default' => '1', 'comment' => 'pay_type 结算类型：1：预付款；2：定时分期付款；3：项目验收后付款；4：按子项目分期付款'])
            ->addColumn('instructions', 'text', ['null' => true, 'comment' => '说明'])
            ->addColumn('status', 'integer',
                [
                    'null' => true,
                    'limit' => 11,
                    'default' => '0',
                    'comment' => 'status 状态：0：新订单；1：待确认；2：已确认；3：制作中；4：交付中；5：已完结'
                ])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}