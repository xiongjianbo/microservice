<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Comment extends Migrator
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
        $table = $this->table('comment');
        $table->setComment('评论表')
            ->addColumn('content', 'text', ['comment' => '评论内容'])
            ->addColumn('commentable_id', 'integer', ['limit' => '11', 'signed' => false, 'comment' => '职位拥有的规则'])
            ->addColumn('commentable_type', 'string', ['limit' => 32, 'comment' => '所属模型的类型'])
            ->addColumn('personnel_id', 'integer', ['signed' => false, 'limit' => 11, 'comment' => '员工ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
