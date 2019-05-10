<?php

use think\migration\Migrator;

class salaryInsurance extends Migrator
{
    public function change()
    {
        $this->table('salary_insurance')
            ->setComment('薪酬社保表')
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('name', 'string', ['null' => true, 'limit' => 32, 'comment' => 'name 保险项目'])
            ->addColumn('type', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'type 计算方式：1：固定金额；2按月薪比例'])
            ->addColumn('personal', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'personal 个人扣款'])
            ->addColumn('company', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company 公司扣款'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}