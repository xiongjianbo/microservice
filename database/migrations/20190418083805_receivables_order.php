<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ReceivablesOrder extends Migrator
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
        $this->table('receivables_order')
            ->setComment('收款单表')
            ->addColumn('number', 'string', ['limit' => 20, 'default' => '', 'comment' => '编号'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '客户id'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1,
                'comment' => '状态:1-未确认;2-已确认;3-已完结;'])
            ->addColumn('description', 'text', ['comment' => '说明'])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '价格'])
            ->addColumn('price_unit', 'string', ['limit' => 3, 'comment' => '价格单位'])
            ->addColumn('attachment_uri', 'json', ['null' => true, 'comment' => '付款凭证地址'])
            ->addColumn('expect_payment_time', 'timestamp', ['null' => true, 'comment' => '预计付款日期'])
            ->addColumn('payment_time', 'timestamp', ['null' => true, 'comment' => '实际付款日期'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
