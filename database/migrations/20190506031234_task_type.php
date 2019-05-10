<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TaskType extends Migrator
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
        $this->table('task_type')
            ->setComment('任务类别')
            ->addColumn('parent_id', 'integer', ['limit' => 10, 'comment' => '父分类ID','default'=>0])
            ->addColumn('name', 'string', ['limit' => 20, 'comment' => '分类名称'])
            ->addColumn('sort', 'integer', ['limit' => 10, 'comment' => '排序','default'=>0])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
