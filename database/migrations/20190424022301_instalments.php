<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class Instalments extends Migrator
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
        $this->table('instalments')
            ->setComment('分期付款表 关联order表')
            ->addColumn('order_id', 'integer', ['limit' => 10, 'comment' => '订单表ID'])
            ->addColumn('payment_date', 'integer', ['limit' => 10, 'comment' => '账单日', 'null' => true])
            ->addColumn('amount','decimal', ['precision' => 10, 'scale' => 2, 'comment' => '支付金额'])
            ->addColumn('unit', 'string', ['limit' => '5', 'default' => 'CNY', 'comment' => '货币单位'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
