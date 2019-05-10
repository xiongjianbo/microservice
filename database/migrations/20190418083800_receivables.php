<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Receivables extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('receivables')
            ->setComment('收款明细表')
            ->addColumn('order_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '订单ID'])
            ->addColumn('project_id', 'integer', ['limit' => 11, 'null' => true, 'signed' => false, 'comment' => '项目ID'])
            ->addColumn('task_id', 'integer', ['limit' => 11, 'null' => true, 'signed' => false, 'comment' => '任务ID'])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '价格'])
            ->addColumn('price_unit', 'string', ['limit' => 3, 'comment' => '价格单位'])
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '说明（预付款、分期付款）'])
            ->addColumn('receivables_order_id', 'integer', ['limit' => 11, 'null' => true, 'comment' => '收款单id'])
            ->addColumn('customer_id', 'integer', ['limit' => 11, 'null' => true, 'comment' => '客户id'])
            ->addColumn('payment', 'integer', ['limit' => 1, 'default' => 0, 'comment' => '是否收款'])
            ->addColumn('expect_payment_time', 'timestamp', ['null' => true, 'comment' => '预计付款日期'])
            ->addColumn('send_time', 'timestamp', ['null' => true, 'comment' => '发送收款单时间'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
