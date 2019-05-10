<?php

use think\migration\Migrator;
use think\migration\db\Column;

class PresetProgram extends Migrator
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
        $this->table('preset_program')
            ->setComment('程序业务预设表')
            ->addColumn('type', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'type 类型：1：平台；2：分类；3：模块；4：功能'])
            ->addColumn('parent_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => '父级ID'])
            ->addColumn('name', 'string', ['null' => true, 'limit' => 32, 'comment' => 'name 名称'])
            ->addColumn('name_en', 'string', ['null' => true, 'limit' => 32, 'comment' => '英语 名称'])
            ->addColumn('name_ko', 'string', ['null' => true, 'limit' => 32, 'comment' => '韩语 名称'])
            ->addColumn('name_ja', 'string', ['null' => true, 'limit' => 32, 'comment' => '日语 名称'])
            ->addColumn('price_min', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'price_min 最小报价'])
            ->addColumn('price_max', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'price_max 最大报价'])
            ->addColumn('day_min', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'day_min 最小工期'])
            ->addColumn('day_max', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'day_max 最大工期'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
