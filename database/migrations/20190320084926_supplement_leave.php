<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SupplementLeave extends Migrator
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
        $table = $this->table('supplement_leave');
        $table->setComment('请假申请表')
            ->addColumn('type', 'integer', ['limit' => 1, 'signed' => false,
                'comment' => '类型：1-年假;2-事假;3-病假;4-调休;5-产假;6-陪产假;7-婚假;8-丧假;9-哺乳假;10-其他'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '发起者员工ID'])
            ->addColumn('start_time', 'timestamp', ['comment' => '开始时间', 'null' => true])
            ->addColumn('end_time', 'timestamp', ['comment' => '结束时间', 'null' => true])
            ->addColumn('reason', 'text', ['comment' => '情况说明'])
            ->addColumn('attachment_uri', 'json', ['null' => true, 'comment' => '附件地址'])
            ->addColumn('time_len', 'json', ['comment' => '请假时长描述  [{"day":"2019-03-20","hour":8}]'])
            ->addColumn('current_status', 'integer', ['limit' => 1, 'default' => 0,
                'comment' => '审核状态:0-等待审核;1-审核通过;(-1)-审核不通过'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
