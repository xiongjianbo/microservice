<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class Customer extends Migrator
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
        $table = $this->table('customer');
        $table->setComment('客户表')
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '名称'])
            ->addColumn('abbreviation', 'string', ['limit' => 30, 'comment' => '简称', 'null' => true])
            ->addColumn('address', 'string', ['limit' => 150, 'comment' => '地址', 'null' => true])
            ->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '类别：1-企业；2-个人；'])
            ->addColumn('tel', 'string', ['limit' => 20, 'comment' => '电话', 'null' => true])
            ->addColumn('email', 'string', ['limit' => 50, 'comment' => '邮箱', 'null' => true])
            ->addColumn('business', 'string', ['limit' => 100, 'comment' => '业务范围', 'null' => true])
            ->addColumn('statement_date', 'integer', ['limit' => 2, 'comment' => '账单日'])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '所属公司ID','default'=>0])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
