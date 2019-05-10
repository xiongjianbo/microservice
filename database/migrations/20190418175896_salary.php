<?php

use think\migration\Migrator;

class salary extends Migrator
{
    public function change()
    {
        $this->table('salary')
            ->setComment('薪酬表')
            ->addColumn('basic', 'decimal',
                ['precision' => 32, 'scale' => 4, 'null' => true, 'comment' => 'basic 基本工资'])
            ->addColumn('position', 'decimal',
                ['precision' => 32, 'scale' => 4, 'null' => true, 'comment' => 'position 岗位工资'])
            ->addColumn('traffic', 'decimal',
                ['precision' => 32, 'scale' => 4, 'null' => true, 'comment' => 'traffic 交通补贴'])
            ->addColumn('other', 'decimal',
                ['precision' => 32, 'scale' => 4, 'null' => true, 'comment' => 'other 其他补贴'])
            ->addColumn('currency', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'currency 薪资单位：1：人民币；2：日元；3：美元；4：欧元；'])
            ->addColumn('company_id', 'integer', ['limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('plan_id', 'integer', ['limit' => 11, 'comment' => 'plan_id 所属方案ID'])
            ->addColumn('level_id', 'integer', ['limit' => 11, 'null' => true, 'comment' => 'level_id 所属等级ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}