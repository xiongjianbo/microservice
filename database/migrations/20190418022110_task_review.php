<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TaskReview extends Migrator
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
        $this->table('task_review')
            ->setComment('任务交付表')
            ->addColumn('number', 'string', ['limit' => 20, 'default' => '', 'comment' => '编号'])
            ->addColumn('task_id', 'integer', ['limit' => 11, 'comment' => '关联的ID'])
            ->addColumn('attachment_uri', 'json', ['null' => true, 'comment' => '附件地址'])
            ->addColumn('description', 'text', ['comment' => '说明'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();

    }
}
