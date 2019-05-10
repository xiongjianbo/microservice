<?php

use think\migration\Migrator;

class salaryMerits extends Migrator
{
    public function change()
    {
        $this->table('salary_merits')
            ->setComment('薪酬绩效表')
            ->addColumn('plan_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'plan_id 所属方案ID'])
            ->addColumn('type', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'type 绩效类型：1：绩效日；'])
            ->addColumn('range_key', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'range_key 范围：针对不同绩效方式的范围 range<=merits<nextrange'])
            ->addColumn('merits', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'merits 绩效：针对不同绩效方式的值'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}