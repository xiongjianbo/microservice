<?php

use think\migration\Migrator;

class department extends Migrator
{
    public function change()
    {
        $this->table('department')
            ->setComment('部门表')
            ->addColumn('p_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'p_id 父级ID'])
            ->addColumn('name', 'string', ['null' => true, 'limit' => 128, 'comment' => 'name 部门名称'])
            ->addColumn('path', 'string', ['null' => true, 'limit' => 32, 'comment' => 'path 层级'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('is_salary', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'is_salary 是否设置薪酬'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}