<?php

use think\migration\Migrator;

class company extends Migrator
{
    public function change()
    {
        $this->table('company')
            ->setComment('公司表')
            ->addColumn('name', 'string', ['null' => true, 'limit' => 128, 'comment' => 'name 公司名称'])
            ->addColumn('rules', 'text', ['null' => true, 'comment' => 'rules 公司拥有的规则'])
            ->addColumn('keyword', 'string', ['null' => true, 'limit' => 32, 'comment' => 'key 密钥'])
            ->addColumn('is_salary', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'is_salary 是否设置薪酬'])
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(['name', 'keyword'], ['unique' => true])
            ->create();
    }
}