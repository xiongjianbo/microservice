<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Projects extends Migrator
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
        $this->table('projects')
            ->setComment('项目表')
            ->addColumn('parent_id', 'integer', ['default' => '0', 'limit' => 11, 'comment' => '父级ID'])
            ->addColumn('number', 'string',
                ['limit' => 32, 'comment' => 'number 项目编号', 'default' => 'XM201905061056221111'])
            ->addColumn('order_id', 'integer', ['limit' => 11, 'default' => '0', 'comment' => '订单ID'])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('customer_id', 'integer', ['limit' => 11, 'comment' => 'customer_id 客户ID'])
            ->addColumn('project_type_id', 'integer', ['limit' => 11, 'comment' => 'project_type_id 项目类别ID'])
            ->addColumn('name', 'string', ['limit' => 32, 'comment' => 'name 项目名称'])
            ->addColumn('done_date', 'integer', ['null' => true, 'limit' => 11, 'comment' => '项目交付日期'])
            ->addColumn('begin_date', 'integer', ['limit' => 11, 'comment' => '预计开始日期'])
            ->addColumn('expect_date', 'integer', ['limit' => 11, 'comment' => '预计完成日期'])
            ->addColumn('expect_money', 'decimal', ['precision' => 11, 'scale' => 2, 'comment' => 'expect_money 预算'])
            ->addColumn('currency_id', 'string', ['limit' => 11, 'comment' => 'currency_id 所属货币ID'])
            ->addColumn('personnel_id', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'personnel_id 项目负责人ID'])
            ->addColumn('instructions', 'text', ['null' => true, 'comment' => '说明'])
            ->addColumn('status', 'integer',
                [
                    'null' => true,
                    'limit' => 11,
                    'default' => '0',
                    'comment' => 'status 状态：0：准备中；1：制作中；2：交付中；3：修改中；4：已完结'
                ])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
