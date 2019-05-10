<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CustomerInfo extends Migrator
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
        $this->table('customer_info')
            ->setComment('客户联系人表  一个客户可以有多个联系信息')
            ->addColumn('customer_id', 'integer', ['limit' => 10, 'comment' => '客户ID', 'default' => 0,])
            ->addColumn('is_default', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '是否默认的联系信息，只能有一个默认的信息'])
            ->addColumn('contact_person', 'string', ['limit' => 30, 'comment' => '联系人', 'null' => true])
            ->addColumn('telephone', 'string', ['limit' => 16, 'comment' => '联系电话', 'null' => true])
            ->addColumn('skype', 'string', ['limit' => 50, 'comment' => 'skype', 'null' => true])
            ->addColumn('email', 'string', ['limit' => 120, 'comment' => '邮箱', 'null' => true])
            ->addColumn('other_contact_info', 'string', ['limit' => 120, 'comment' => '其他联系方式，比如微信', 'null' => true])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
