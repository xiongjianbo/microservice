<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Config extends Migrator
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
        $table = $this->table('config');
        $table->addColumn('title', 'string', ['limit' => 100, 'default' => '', 'comment' => '名称'])
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '标识'])
            ->addColumn('value', 'string', ['limit' => 500,'comment' => '值'])
            ->addColumn('description', 'text', ['comment' => '描述', 'null' => true])
            ->addIndex(['name'], ['unique' => true])
            ->create();
    }
}
