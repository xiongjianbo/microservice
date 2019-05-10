<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Currency extends Migrator
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
        $this->table('currency')
            ->setComment('货币表')
            ->addColumn('code', 'string', ['limit' => 2, 'comment' => '货币符号','default'=>''])
            ->addColumn('name', 'string', ['limit' => 20, 'comment' => '货币名称'])
            ->addColumn('desc', 'string', ['limit' => 50, 'comment' => '货币备注'])
            ->addColumn('exchange_rate', 'integer', ['limit' => 255,'default'=>0, 'comment' => '税率'])
            ->addColumn('gst_rate', 'integer', ['limit' => 255,'default'=>0, 'comment' => '增值税率'])
            ->addColumn('fees_gst_rate', 'integer', ['limit' => 255,'default'=>0, 'comment' => '消费税率'])
            ->addColumn('exchange_loss', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '消费税率'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
