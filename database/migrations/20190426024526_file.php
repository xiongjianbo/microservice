<?php

use think\migration\Migrator;
use think\migration\db\Column;

class File extends Migrator
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
        $this->table('file')
            ->setComment('文件表')
            ->addColumn('name', 'string', ['limit' => 20, 'comment' => '文件名'])
            ->addColumn('resource', 'string', ['null' => true, 'limit' => 20, 'comment' => '资源'])
            ->addColumn('resource_id', 'integer', ['null' => true, 'limit' => 10, 'comment' => '资源ID'])
            ->addColumn('router_id', 'integer', ['limit' => 10, 'comment' => '路由ID'])
            ->addColumn('type', 'integer', ['limit' => 255, 'comment' => '1:图片  2：文件', 'default' => 2])
            ->addColumn('size', 'integer', ['limit' => 10, 'comment' => '文件大小', 'default' => 0])
            ->addColumn('ext', 'string', ['limit' => 10, 'comment' => '文件拓展名', 'default' => ''])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
