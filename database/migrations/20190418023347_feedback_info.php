<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FeedbackInfo extends Migrator
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
        $this->table('feedback_info')
            ->setComment('反馈详情表')
            ->addColumn('feedback_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '反馈ID'])
            ->addColumn('content', 'string', ['limit' => 500, 'comment' => '反馈原文'])
            ->addColumn('translate', 'string', ['limit' => 500, 'comment' => '翻译'])
            ->addColumn('feedback_categories', 'string', ['limit' =>'16', 'comment' => '反馈类别：根据所关联的（项目/任务）判断'])
            ->addColumn('reason', 'integer', ['limit' => 1, 'comment' => '原因:1-规则不符;2-品质不佳;3-需求更改;'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '责任人员工ID，为0表示客户原因'])
            ->create();

    }
}
