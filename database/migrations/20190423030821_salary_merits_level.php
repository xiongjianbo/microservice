<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SalaryMeritsLevel extends Migrator
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
        $this->table('salary_merits_level')
            ->setComment('薪酬绩效等级加成表')
            ->addColumn('plan_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'plan_id 所属方案ID'])
            ->addColumn('level_id', 'integer', ['limit' => 11, 'null' => true, 'comment' => '所属等级ID'])
            ->addColumn('bonus', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'bonus 奖金加成系数 单位%'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
