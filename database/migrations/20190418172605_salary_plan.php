<?php

use think\migration\Migrator;

class salaryPlan extends Migrator
{
    public function change()
    {
        $this->table('salary_plan')
            ->setComment('薪酬计划表')
            ->addColumn('type', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'type 类型：1:底薪计划；2：奖金计划；3：扣款计划；'])
            ->addColumn('name', 'string', ['null' => true, 'limit' => 128, 'comment' => 'name 计划名称'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('apply', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'apply 需要应用的类型:1-个人;2-职位;3-部门;4-全员'])
            ->addColumn('apply_id', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'apply_id 需要应用的ID：根据apply字段对应表'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}