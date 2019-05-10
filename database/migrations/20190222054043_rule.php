<?php

use think\migration\Migrator;

class Rule extends Migrator
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
        $table = $this->table('rule');
        $table->addColumn('title', 'string', ['limit' => 25, 'default' => '', 'comment' => '规则名称'])
            ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '定义'])
            ->addColumn('level', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '级别:1-模块,2-控制器,3-操作'])
            ->addColumn('p_id', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '父级ID', 'signed' => false])
            ->addColumn('scope', 'string', ['limit' => 10, 'default' => 1,
                'comment' => '权限范围：1-全部；2-部门；3-个人；'])
            ->addIndex(['name'], ['unique' => true])
            ->create();
    }
}
