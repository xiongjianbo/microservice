<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Task extends Migrator
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
        $table = $this->table('task');
        $table->setComment('任务表')
            ->addColumn('p_id', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '父级任务ID'])
            ->addColumn('project_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '项目/子项目ID'])
            ->addColumn('task_number', 'string', ['limit' => 20, 'default' => '', 'comment' => '任务编号'])
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '任务名称'])
            ->addColumn('status', 'integer', ['limit' => 1,
                'comment' => '项目阶段:1-待分配;2-待接受;3-未开始;4-进行中;5-审核中;6-已完成;7-已终止;', 'default' => 1])
            ->addColumn('type', 'string', ['limit' => 20, 'comment' => '类别'])
            ->addColumn('style', 'integer', ['limit' => 1, 'comment' => '任务类型:1-准备任务;2-制作任务;3-修改任务;'])
            ->addColumn('way', 'integer', ['limit' => 1, 'comment' => '发布方式:1-任务大厅;2-指定专职', 'default' => 1])
            ->addColumn('level', 'integer', ['limit' => 1, 'comment' => '任务等级(难度)：1-5'])
            ->addColumn('performance_day', 'integer', ['limit' => 3, 'comment' => '绩效日'])
            ->addColumn('price', 'integer', ['limit' => 10, 'comment' => '费用'])
            ->addColumn('price_unit', 'string', ['limit' => 3, 'comment' => '价格单位'])
            ->addColumn('balance_day', 'date', ['comment' => '结算日', 'default' => "2019-05-01", "null" => true])
            ->addColumn('range', 'json', ['null' => true, 'comment' => '业务范围'])
            ->addColumn('description', 'text', ['comment' => '说明'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '负责人ID'])
            ->addColumn('schedule', 'integer', ['limit' => 255, 'comment' => '进度', 'default' => 0])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '所属公司ID', 'default' => 0])
            ->addColumn('expect_start_time', 'timestamp', ['null' => true, 'comment' => '预计开始日期'])
            ->addColumn('expect_finish_time', 'timestamp', ['null' => true, 'comment' => '预计完成日期'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
