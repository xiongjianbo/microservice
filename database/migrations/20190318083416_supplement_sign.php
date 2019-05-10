<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SupplementSign extends Migrator
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
        $table = $this->table('supplement_sign');
        $table->setComment('补卡申请表')
            ->addColumn('type', 'enum', ['values' => [1, 2], 'comment' => '[1,2]1上班,2下班'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '发起者员工ID'])
            ->addColumn('day', 'date', ['comment' => '申请时间'])
            ->addColumn('sign_time', 'time', ['comment' => '补卡时间'])
            ->addColumn('reason', 'text', ['comment' => '情况说明'])
            ->addColumn('attachment_uri', 'json', ['null' => true, 'comment' => '附件地址'])
            ->addColumn('current_status', 'integer', ['limit' => 1, 'default' => 0,
                'comment' => '审核状态:0-等待审核;1-审核通过;(-1)-审核不通过'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
