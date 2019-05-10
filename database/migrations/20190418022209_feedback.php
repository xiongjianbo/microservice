<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Feedback extends Migrator
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
        $this->table('feedback')
            ->setComment('反馈记录表')
            ->addColumn('number', 'string', ['limit' => 20, 'default' => '', 'comment' => '编号'])
            ->addColumn('feedback_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '所属模型的类型（任务、项目）,类别：0-（project（项目））；1-（task（任务））'])
            ->addColumn('feedback_id', 'integer', ['limit' => 11, 'comment' => '关联的（项目/任务）ID'])
            ->addColumn('customer_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '客户ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
