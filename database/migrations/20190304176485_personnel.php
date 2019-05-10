<?php

use think\migration\Migrator;

class personnel extends Migrator
{
    public function change()
    {
        $this->table('personnel')
            ->setComment('员工表')
            ->addColumn('username', 'string', ['null' => true, 'limit' => 32, 'comment' => 'username 账号'])
            ->addColumn('password', 'string', ['null' => true, 'limit' => 32, 'comment' => 'password 密码'])
            ->addColumn('type', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'type 员工类别:0-超管;1-员工;2-兼职;(-2)-兼职待审核;(-1)-离职员工'])
            ->addColumn('number', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'number 工号'])
            ->addColumn('name', 'string', ['null' => true, 'limit' => 32, 'comment' => 'name 姓名'])
            ->addColumn('sex', 'integer',
                ['null' => true, 'limit' => 11, 'default' => '1', 'comment' => 'sex 性别:1-男;2-女;'])
            ->addColumn('phone', 'string', ['null' => true, 'limit' => 32, 'comment' => 'phone 手机号码'])
            ->addColumn('email', 'string', ['null' => true, 'limit' => 32, 'comment' => 'email 邮箱'])
            ->addColumn('company_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'company_id 所属公司ID'])
            ->addColumn('department_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'department_id 部门ID'])
            ->addColumn('position_id', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'position_id 职位ID'])
            ->addColumn('salary_level_id', 'integer',
                ['null' => true, 'limit' => 11, 'comment' => 'salary_level_id 薪资等级ID'])
            ->addColumn('is_salary', 'integer', ['null' => true, 'limit' => 11, 'comment' => 'is_salary 是否设置薪酬'])
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(['username'], ['unique' => true])
            ->create();
    }
}