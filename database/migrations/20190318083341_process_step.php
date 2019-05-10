<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ProcessStep extends Migrator
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
        $table = $this->table('process_step');
        $table->setComment('具体的流程步骤表')
            ->addColumn('supplement_id', 'integer', [
                'limit' => 11, 'signed' => false, 'comment' => '外键ID', 'default' => 1])
            ->addColumn('supplement_type', 'string', ['limit' => 255, 'comment' => '所属模型的类型 其他表的表名'])
            ->addColumn('process_rule_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '1流程规则表的ID'])
            ->addColumn('verify_target', 'json', ['comment' => 'eg:[1,2,3]  待审核人员员工ID'])
            ->addColumn('verify_status', 'integer', ['limit' => 1, 'default' => 0,
                'comment' => '审核状态:0-等待审核;1-审核通过;(-1)-审核不通过'])
            ->addColumn('verify_personnel_id', 'integer', ['null' => true, 'limit' => 11, 'signed' => false,
                'comment' => '审核人的员工ID'])
            ->addColumn('verify_time', 'timestamp', ['null' => true, 'comment' => '审核人的员工ID'])
            ->addColumn('verify_content', 'text', ['null' => true, 'comment' => '审核批注'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
