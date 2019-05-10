<?php

use think\migration\Migrator;

class position extends Migrator
{
    public function change()
    {
        $this->table('position')
            ->setComment('职位表')
            ->addColumn('title', 'string', ['null' => true, 'limit' => 128, 'comment' => 'title 职位名称'])
            ->addColumn('status', 'integer',
                ['null' => true, 'limit' => 11, 'default' => '1', 'comment' => 'status 1正常，0禁用'])
            ->addColumn('rules', 'text', ['null' => true, 'comment' => 'rules 职位拥有的规则'])
            ->addColumn('description', 'text', ['null' => true, 'comment' => 'description 岗位说明书'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('department_id', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'department_id 所属部门ID'])
            ->addColumn('is_salary', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'is_salary 是否设置薪酬'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}