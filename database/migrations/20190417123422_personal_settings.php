<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class personalSettings extends Migrator
{
    public function change()
    {
        $table = $this->table('personal_settings');
        $table->setComment('个人设置表')
            ->addColumn('is_welcome', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 0,
                'comment' => '选中状态:0-未选中,1-已选中'
            ])
            ->addColumn('is_news', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 0,
                'comment' => '选中状态:0-未选中,1-已选中'
            ])
            ->addColumn('is_task', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 0,
                'comment' => '选中状态:0-未选中,1-已选中'
            ])
            ->addColumn('is_schedule', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 0,
                'comment' => '选中状态:0-未选中,1-已选中'
            ])
            ->addColumn('is_open', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '是否启用二级密码:0-不启用,1-启用',
                'default' => 0,
            ])
            ->addColumn('secondary_password', 'string', ['limit' => 32, 'comment' => '二级密码', 'default' => ''])
            ->addColumn('personnel_id', 'integer', ['limit' => 11, 'signed' => false, 'comment' => '所属员工ID', 'default' => 0])
            ->addSoftDelete()
            ->addTimestamps()
            ->create();
    }
}