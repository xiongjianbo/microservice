<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Accessory extends Migrator
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
        $this->table('accessory')
            ->setComment('附件表')
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('source_type', 'integer', ['null' => true, 'limit' => 11, 'comment' => '来源类型：1：订单；2：项目；3：任务；4：项目交付 5:提交任务'])
            ->addColumn('source_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => '来源类型Id'])
            ->addColumn('file_id', 'string', ['null' => true, 'limit' => 128, 'comment' => '附件资源ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
