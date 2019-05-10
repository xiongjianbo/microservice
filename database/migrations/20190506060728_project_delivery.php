<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class ProjectDelivery extends Migrator
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
        $this->table('project_delivery')
            ->setComment('项目交付')
            ->addColumn('number', 'char', ['limit' => 20, 'comment' => 'number 交付单编号'])
            ->addColumn('project_id', 'integer', ['limit' => 11, 'comment' => 'project_id 关联的项目id'])
            ->addColumn('is_collection', 'integer',
                ['limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '是否收款。0未收款，1已收款'])
            ->addColumn('status', 'integer', [
                'limit'   => MysqlAdapter::INT_TINY,
                'default' => 0,
                'signed'  => false,
                'comment' => '交付状态。0未验收，1拒绝验收，2已验收'
            ])
            ->addColumn('comment', 'string', ['limit' => 255, 'default' => '', 'comment' => '提交说明'])
            ->addColumn('commit_date', 'integer', ['limit' => 11, 'comment' => '提交日期'])
            ->addColumn('audit_comment', 'string', ['limit' => 255, 'default' => '', 'comment' => '审核意见说明'])
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(['number'], ['unique' => true])
            ->addIndex(['project_id'], ['unique' => true])
            ->create();
    }
}
