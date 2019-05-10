<?php

use think\migration\Migrator;

class Supplier extends Migrator
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
        $table = $this->table('supplier');
        $table->setComment('供应商表')
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '名称'])
            ->addColumn('abbreviation', 'string', ['limit' => 30, 'comment' => '简称', 'null' => true])
            ->addColumn('address', 'string', ['limit' => 150, 'comment' => '地址', 'null' => true])
            ->addColumn('type', 'string', ['limit' => 50, 'comment' => '供应品类'])
            ->addColumn('tel', 'string', ['limit' => 20, 'comment' => '电话', 'null' => true])
            ->addColumn('email', 'string', ['limit' => 50, 'comment' => '邮箱', 'null' => true])
            ->addColumn('contact', 'json', ['null' => true, 'comment' => '联系人'])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '所属公司ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();

    }
}
