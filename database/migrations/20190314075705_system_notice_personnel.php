<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemNoticePersonnel extends Migrator
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
        $table = $this->table('system_notice_personnel');
        $table->setComment('系统公告列表员工查看状态')
            ->addColumn('system_notice_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '公告ID', 'default' => 1])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '员工ID', 'default' => 1])
            ->addColumn('is_read', 'integer', ['default' => 0, 'comment' => '是否已读,0未读,1已读'])
            ->addColumn('read_time', 'datetime', ['null' => true, 'comment' => '阅读时间时间'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();

    }
}
