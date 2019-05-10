<?php

use think\migration\Migrator;

class Log extends Migrator
{
    public function change()
    {
        $table = $this->table('log');
        $table->setComment('日志表')
            ->addColumn('log_type', 'string', ['limit' => 255, 'comment' => '所属模型的类型 其他表的表名'])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '（员工）操作者ID'])
            ->addColumn('customer_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '（客户）操作者ID'])
            ->addColumn('action', 'string', ['comment' => '操作者'])
            ->addColumn('description', 'text', ['comment' => '说明'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '时间'])
            ->create();
    }
}
