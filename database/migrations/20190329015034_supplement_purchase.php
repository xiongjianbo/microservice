<?php

use think\migration\Migrator;

class SupplementPurchase extends Migrator
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
     *6212264402039274943
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('supplement_purchase');
        $table->setComment('采购申请表')
            ->addColumn('order_number', 'string', ['limit' => 20, 'default' => '', 'comment' => '采购单号'])
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '物品名'])
            ->addColumn('parameter', 'string', ['default' => '', 'comment' => '物品参数'])
            ->addColumn('number', 'integer', ['default' => 1, 'limit' => 8, 'comment' => '数量'])
            ->addColumn('unit', 'string', ['limit' => 10, 'comment' => '单位'])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '物品单价'])
            ->addColumn('price_unit', 'string', ['limit' => 3, 'comment' => '价格单位'])
            ->addColumn('supplier_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '供应商ID'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '发起者员工ID'])
            ->addColumn('reason', 'text', ['comment' => '情况说明'])
            ->addColumn('current_status', 'integer', ['limit' => 1, 'default' => 0,
                'comment' => '审核状态:0-等待审核;1-审核通过;(-1)-审核不通过'])
            ->addColumn('purchase_time', 'timestamp', ['null' => true, 'comment' => '采购日期'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
