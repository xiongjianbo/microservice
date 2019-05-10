<?php
/**
 * author: JiangJun
 * CreateTime:2019-05-08 22:07
 */

namespace elastic;

use Elastica\Client;
use Elastica\Document;
use Elastica\Query;
use Elastica\QueryBuilder;
use Elastica\Search;

class Elastic
{
    public static $instance;
    public $esIndex;
    public $esType;
    public $esDoc;

    public function __construct()
    {
        if (empty(self::$instance)) {
            self::$instance = new Client([
                'host' => '192.168.2.105',
                'port' => 9200,
                'curl' => [64 => []]
            ]);
        }
    }

    //获取索引
    public function getIndex($indexName)
    {
        $this->esIndex = self::$instance->getIndex($indexName);
        $this->esType = $this->esIndex->getType('_doc');
        return $this;
    }

    //创建索引
    public function createIndex($indexName)
    {
        $this->getIndex($indexName);
        $this->esIndex->create();
        return $this;
    }

    //删除索引
    public function delIndex($indexName=null)
    {
        if(isset($indexName)){
            $this->getIndex($indexName);
        }
        $this->esIndex->delete();
        return $this;
    }

    //保存单个doc
    public function saveDoc($data,$id='')
    {
        $doc = new Document($id, $data);
        $this->esType->addDocument($doc);
        return $this;
    }

    //保存多个doc
    public function saveDocAll($data)
    {
        $docs=[];
        foreach ($data as $v) {
            $docs[]=new Document($v['id'],$v);
        }
        $this->esType->addDocuments($docs);
        return $this;
    }

    //按id查找doc
    public function getDoc($id)
    {
        $this->esDoc=$this->esType->getDocument($id);
        return $this->esDoc->toArray();
    }


    public function search()
    {
       // return $this->esType->search()->getDocuments();
       $search=new Search(self::$instance);
       $search->addIndex($this->esIndex);
       $search->addType($this->esType);
       $query = new Query();
       $qb=new QueryBuilder();
       //$query->setField
    }

}