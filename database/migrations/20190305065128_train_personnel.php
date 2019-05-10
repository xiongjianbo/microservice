<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class TrainPersonnel extends Migrator
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
        $table = $this->table('train_personnel');
        $table->setComment('培训课程报名表')
            ->addColumn('train_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'comment' => '课程ID'])
            ->addColumn('personnel_id', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'comment' => '员工ID'])
//            ->addForeignKey('train_id','train_class','id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
//            ->addForeignKey('personnel_id','personnel','id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addColumn('score', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '考试结果得分', 'default' => -1])
            ->addColumn('teacher_evaluation', 'enum', [
                'values' => [1, 2, 3],
                'null' => true,
                'comment' => '老师拼假1讲得非常好,2讲得好,3讲得一般'
            ])
            ->addColumn('demand_evaluation', 'enum', [
                'values' => [1, 2, 3],
                'null' => true,
                'comment' => '1非常需要,2需要,3不需要'
            ])
            ->addColumn('evaluation_time', 'timestamp', ['null' => true, 'comment' => '评价时间'])
            ->addColumn('join_time', 'timestamp', ['null' => true, 'comment' => '报名时间'])
            ->addColumn('score_time', 'timestamp', ['null' => true, 'comment' => '考试结果时间'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
