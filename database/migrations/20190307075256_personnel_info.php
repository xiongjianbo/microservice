<?php

use think\migration\Migrator;

class PersonnelInfo extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('personnel_info', ['id' => false, 'primary_key' => ['personnel_id']]);
        $table->setComment('员工信息')
            ->addColumn('personnel_id', 'integer', ['comment' => '主键ID,personnel表的ID'])
            ->addColumn('birthday', 'date', ['null' => true, 'comment' => '出生日期'])
            ->addColumn('id_card', 'string', ['limit' => 18, 'comment' => '身份证号', 'default' => ''])
            ->addColumn('join_time', 'date', ['null' => true, 'comment' => '入职日期'])
            ->addColumn('education', 'enum', [
                'comment' => '教育经历（学历）', 'values' => ['博士', '研究生', '硕士', '本科', '大专', '高中', '其他'],
                'default' => '本科'])
            ->addColumn('major', 'string', ['limit' => 30, 'comment' => '学习专业', 'default' => ''])
            ->addColumn('graduate_school', 'string', ['limit' => 30, 'comment' => '毕业学校', 'default' => ''])
            ->addColumn('graduate_time', 'date', ['null' => true, 'comment' => '毕业时间'])
            ->addColumn('job_description', 'text', ['comment' => '岗位说明书', 'null' => true])
            ->addColumn('address', 'string', ['limit' => 50, 'comment' => '联系地址', 'default' => ''])
            ->addColumn('post', 'string', ['limit' => 6, 'comment' => '邮编', 'default' => ''])
            ->addColumn('household_address', 'string', ['limit' => 50, 'comment' => '户籍地址', 'default' => ''])
            ->addColumn('household_post', 'string', ['limit' => 6, 'comment' => '户籍邮编', 'default' => ''])
            ->addColumn('contact', 'string', ['limit' => 30, 'comment' => '联系人', 'default' => ''])
            ->addColumn('contact_phone', 'string', ['limit' => 14, 'comment' => '联系人电话', 'default' => ''])
            ->addColumn('contact_role', 'string', ['limit' => 10, 'comment' => '与联系人关系', 'default' => ''])
            ->addColumn('contact_address', 'string', ['limit' => 30, 'comment' => '紧急联系人地址', 'default' => ''])
            ->addColumn('contact_post', 'string', ['limit' => 6, 'comment' => '紧急联系人邮编', 'default' => ''])
            ->addColumn('bank_card', 'string', ['limit' => 20, 'comment' => '银行卡号', 'default' => ''])
            ->addColumn('bank_address', 'string', ['limit' => 50, 'comment' => '开户行', 'default' => ''])
            ->addSoftDelete()
            ->create();

    }
}
