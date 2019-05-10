<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class TrainResource extends Migrator
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
        $table = $this->table('train_resource', ['id' => false, 'primary_key' => ['train_id']]);
        $table->setComment('培训课程附件表')
            ->addColumn('train_id', 'integer', ['signed' => false, 'limit' => 11, 'comment' => '主键ID,train_class表的ID'])
            ->addColumn('photo', 'json', ['null' => true, 'comment' => '图片资源地址的uri数组 [{"thumb":"http://xx","name":"图片名字","url":"图片地址"}]'])
            ->addColumn('video', 'json', ['null' => true, 'comment' => '视频资源地址的uri数组 [{"thumb":"http://xx","name":"图片名字","url":"视频地址"}]'])
            ->addColumn('file', 'json', ['null' => true, 'comment' => '附件资源地址的uri数组 [{"thumb":"http://xx","name":"图片名字","url":"文件地址"}]'])
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
