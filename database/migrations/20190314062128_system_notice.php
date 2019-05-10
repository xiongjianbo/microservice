<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemNotice extends Migrator
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
        $table = $this->table('system_notice');
        $table->setComment('系统公告列表')
            ->addColumn('company_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '公司ID', 'default' => 1])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '员工ID', 'default' => 1])
            ->addColumn('is_all', 'integer', ['limit' => 1, 'comment' => '是否全员,1是，0否'])
            ->addColumn('title', 'string', ['limit' => 255, 'comment' => '公告标题'])
            ->addColumn('begin_time', 'datetime', ['comment' => '开始时间'])
            ->addColumn('content', 'text', ['comment' => '公告内容'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
