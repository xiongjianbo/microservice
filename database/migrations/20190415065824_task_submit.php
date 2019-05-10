<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TaskSubmit extends Migrator
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
        $table = $this->table('task_submit');
        $table->setComment('任务表')
            ->addColumn('task_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '任务ID'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 0,
                'comment' => '审核状态:0-等待审核;1-审核通过;(-1)-审核不通过'])
            ->addColumn('description', 'text', ['comment' => '说明', 'null' => true])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '负责人ID', 'default' => 0])
            ->addColumn('pass_time', 'integer', ['limit' => 11, 'null' => true, 'comment' => '审核通过时间'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}