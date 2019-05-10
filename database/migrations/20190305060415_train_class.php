<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class TrainClass extends Migrator
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
        $table = $this->table('train_class');
        $table->setComment('培训课程表')
            ->addColumn('company_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'signed' => false, 'comment' => '公司ID', 'default' => 1])
            ->addColumn('name', 'string', ['limit' => 255, 'comment' => '课程名称'])
            ->addColumn('begin_time', 'datetime', ['comment' => '课程开始时间'])
            ->addColumn('teacher', 'string', ['limit' => 255, 'comment' => '课程讲师'])
            ->addColumn('address', 'string', ['limit' => 255, 'comment' => '培训地点'])
            ->addColumn('target', 'json', ['comment' => '培训对象 {"is_all": false,"personnel": [1,2,3]}', 'null' => true])
            ->addColumn('join_type', 'enum', [
                'values' => [1, 2, 3],'comment' => '参与类型:1-自愿参与;2-强制参与;3-指定人员强制+其他部分自愿'])
            ->addColumn('time_length', 'string', ['limit' => 20, 'comment' => '培训时长'])
            ->addColumn('most_personnel', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_SMALL, 'comment' => '人数限制,0表示无上限'])
            ->addColumn('current_status', 'enum', ['values' => [2, 3, 4], 'comment' => '状态：2-报名中;3-进行中;4-已结束'])
            ->addColumn('description', 'text', ['comment' => '课程描述', 'null' => true])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
