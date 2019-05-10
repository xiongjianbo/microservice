<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Program extends Migrator
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
        $this->table('program')
            ->setComment('功能程序表')
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('source_type', 'integer', ['null' => true, 'limit' => 11, 'comment' => '来源类型：1：订单；2：项目；3：任务'])
            ->addColumn('source_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => '来源类型Id'])
            ->addColumn('program_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => ' 程序功能ID'])
            ->addColumn('module_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => '模块ID'])
            ->addColumn('category_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => '分类ID'])
            ->addColumn('platform_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => '平台ID'])
            ->addColumn('price', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'price 程序价格'])
            ->addColumn('status', 'integer',
                ['null' => true, 'limit' => 11, 'default' => '0', 'comment' => 'status 状态：0：未完成；1：已完成；'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
