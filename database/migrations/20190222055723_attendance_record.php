<?php

use think\migration\Migrator;

class AttendanceRecord extends Migrator
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
        $table = $this->table('attendance_record');
        $table->addColumn('number', 'integer', ['limit' => 8, 'comment' => '工号'])
            ->addColumn('date', 'date', ['comment' => '日期'])
            ->addColumn('start', 'time', ['null' => true, 'comment' => '上班时间'])
            ->addColumn('end', 'time', ['null' => true, 'comment' => '下班时间'])
            ->addColumn('work', 'decimal', ['precision' => 4, 'scale' => 3, 'default' => 0, 'comment' => '出勤时长（天数）'])
            ->addColumn('leave', 'decimal', ['precision' => 4, 'scale' => 3, 'default' => 0, 'comment' => '请假时长（天数）'])
            ->addColumn('leave_type', 'integer', ['limit' => 1, 'null' => true, 'signed' => true, 'comment' => '请假类型：1,2,3'])
            ->addColumn('late', 'time', ['null' => true, 'comment' => '迟到时长'])
            ->addColumn('left_early', 'time', ['null' => true, 'comment' => '早退时长'])
            ->addColumn('eight', 'integer', ['limit' => 1, 'null' => true, 'signed' => true, 'comment' => '是否八点后下班'])
            ->addColumn('ten', 'integer', ['limit' => 1, 'null' => true, 'signed' => true, 'comment' => '是否十点后下班'])
            ->addColumn('weekend', 'decimal', ['precision' => 2, 'default' => 0, 'scale' => 1, 'signed' => true,
                'comment' => '周末加班天数（大于3小时小于8小时算半天，大于8小时算一天）'])
            ->create();
    }
}
