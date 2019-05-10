<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ProcessRule extends Migrator
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
        $table = $this->table('process_rule');
        $table->setComment('流程规则表')
            ->addColumn('company_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '公司ID', 'default' => 1])
            ->addColumn('p_id', 'integer', ['limit' => 11, 'signed' => false,
                'comment' => '父级ID:0-target表示发起者;不为0-target表示审批者', 'default' => 0])
            ->addColumn('type', 'enum', ['values' => [1, 2, 3, 4, 5],'comment' => '类型:1-请假流程;2-补卡流程;3-采购申请流程;4-人力申请流程;5-任务提交审核流程'])
            ->addColumn('level', 'enum', ['values' => [1, 2, 3, 4],
                'comment' => '范围类型:1-个人;2-职位;3-部门;4-全员'])
            ->addColumn('target', 'json', ['comment' => 'eg:[1,2,3] 目标ID,个人=>personnel_id,职位=>position_id,部门=>department_id,全员=>公司ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
