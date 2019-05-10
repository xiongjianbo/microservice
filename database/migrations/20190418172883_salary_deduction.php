<?php

use think\migration\Migrator;

class salaryDeduction extends Migrator
{
    public function change()
    {
        $this->table('salary_deduction')
            ->setComment('薪酬扣款表')
            ->addColumn('insurance', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'insurance 社保扣款'])
            ->addColumn('insurance_type', 'integer', [
                'null' => true,
                'limit' => 11,
                'comment' => 'insurance_type 社保扣款类型：1：固定金额；2：根据社保设置（指定基数）；3：根据社保设置（按实薪）'
            ])
            ->addColumn('late', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'late 迟到扣款'])
            ->addColumn('late_type', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'late_type 迟到扣款类型：1：固定金额；2：按日薪比例；'])
            ->addColumn('early', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'early 早退扣款'])
            ->addColumn('early_type', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'early_type 早退扣款类型：1：固定金额；2：按日薪比例；'])
            ->addColumn('thing_leave', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'thing_leave 事假扣款'])
            ->addColumn('thing_leave_type', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'thing_leave_type 事假扣款类型：1：固定金额；2：按日薪比例；'])
            ->addColumn('sick_leave', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'sick_leave 病假扣款'])
            ->addColumn('sick_leave_type', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'sick_leave_type 病假扣款类型：1：固定金额；2：按日薪比例；'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('plan_id', 'integer', ['limit' => 11, 'comment' => 'plan_id 所属方案ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}