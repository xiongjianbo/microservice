<?php

use think\migration\Migrator;

class salaryLevel extends Migrator
{
    public function change()
    {
        $this->table('salary_level')
            ->setComment('薪酬等级表')
            ->addColumn('position_id', 'string', ['null' => true, 'limit' => 32, 'comment' => 'position_id 所属职位ID'])
            ->addColumn('name', 'string', ['null' => true, 'limit' => 32, 'comment' => 'name 薪酬等级名称'])
            ->addColumn('level', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'level 薪酬等级'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}