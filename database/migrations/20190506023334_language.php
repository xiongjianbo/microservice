<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

class Language extends Migrator
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
        $this->table('language')
            ->setComment('公司语言设置')
            ->addColumn('company_id', 'integer', ['limit' => 11 ,'comment' => 'company_id 公司ID'])
            ->addColumn('chinese', 'integer', ['limit' => MysqlAdapter::INT_TINY,'default'=>1, 'comment' => 'chinese 是否启用中文'])
            ->addColumn('english', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default'=>0,'comment' => 'english 是否启用英文'])
            ->addColumn('japanese', 'integer', ['limit' => MysqlAdapter::INT_TINY,'default'=>0, 'comment' => 'japanese 是否启用日文'])
            ->addTimestamps()
            ->addIndex(['company_id'], ['unique' => true])
            ->create();
    }
}
