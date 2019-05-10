<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SkillCategory extends Migrator
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
        $this->table('skill_category')
            ->setComment('技能类别表')
            ->addColumn('name', 'string', ['limit' => 20, 'comment' => '中文技能类别名称'])
            ->addColumn('name_en', 'string', ['limit' => 20, 'comment' => '英文技能类别名称'])
            ->addColumn('name_ko', 'string', ['limit' => 20, 'comment' => '韩语技能类别名称'])
            ->addColumn('name_ja', 'string', ['limit' => 20, 'comment' => '日语技能类别名称'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
