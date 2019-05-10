<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RecruitInner extends Migrator
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
        $table = $this->table('recruit_inner');
        $table->setComment('招聘内推表')
            ->addColumn('personnel_id', 'integer', ['signed' => false, 'limit' => 11, 'comment' => '员工ID'])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '公司ID', 'default' => 1])
            ->addColumn('name', 'string', ['limit' => 30, 'comment' => '被推荐人姓名'])
            ->addColumn('phone', 'string', ['limit' => 30, 'comment' => '被推荐人电话'])
            ->addColumn('resume_uri', 'string', ['limit' => 30, 'comment' => '被推荐人简历'])
            ->addColumn('current_status', 'enum', ['values' => [1, 2, 3, 4], 'comment' => '1已推荐(等待面试),2面试已结束,3已录用,4未录用', 'default' => 1])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
