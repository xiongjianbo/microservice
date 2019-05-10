<?php

use think\migration\Migrator;

class salaryTax extends Migrator
{
    public function change()
    {
        $this->table('salary_tax')
            ->setComment('薪酬个税表')
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('type', 'integer',
                ['null' => true, 'limit' => 11,'comment' => '类型：1：超额累进税制-2019版'])
            ->addColumn('tax_begin', 'integer', ['null' => true, 'limit' => 11, 'comment' => '个人所得税起征点'])
            ->addColumn('range_key', 'decimal', [
                'precision' => 32,
                'scale' => 2,
                'null' => true,
                'limit' => 11,
                'comment' => 'range_key 范围标识 range<value<=nextrange'
            ])
            ->addColumn('scale', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'scale 预扣税率 %'])
            ->addColumn('fast_number', 'decimal',
                ['precision' => 32, 'scale' => 2, 'null' => true, 'limit' => 11, 'comment' => 'fast_number 速算扣除数'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}