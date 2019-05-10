<?php

namespace app\es\controller;

use elastic\Elastic;
use Elastica\Client;
use think\Controller;
use think\Request;

class EsController extends Controller
{
    public function test()
    {
        $elasticaClient = new Client([
            'host' => '192.168.2.105',
            'port' => 9200,
            'curl' => [64 => []]
        ]);
        $esIndex = $elasticaClient->getIndex('twitter');
        $elasticaType = $esIndex->getType('_doc');

        //$esIndex->create();

        // The Id of the document
        $id = 2;

        // Create a document
        $tweet = array(
            'id'       => $id,
            'user'     => array(
                'name'     => 'jiang',
                'fullName' => 'jiang jun'
            ),
            'msg'      => '我想学好',
            'tstamp'   => '1238081389',
            'location' => '41.12,-71.34'
        );
        // First parameter is the id of document.
        $tweetDocument = new \Elastica\Document($id, $tweet);
        $elasticaType->addDocument($tweetDocument);

        $elasticaType->getIndex()->refresh();

    }

    public function test2()
    {
        $es = new Elastic();
        $data = [
            [
            'id'       => 10,
            'user'     => array(
                'name'     => '10',
                'fullName' => 'aaa'
            ),
            'msg'      => '777ccc',
            'tstamp'   => '1238081389',
            'location' => '41.12,-71.34'
            ],
            [
            'id'       => 11,
            'user'     => array(
                'name'     => '11',
                'fullName' => 'bbb'
            ),
            'msg'      => '777ccc',
            'tstamp'   => '1238081389',
            'location' => '41.12,-71.34'
            ],[
            'id'       => 12,
            'user'     => array(
                'name'     => '12',
                'fullName' => 'ccc'
            ),
            'msg'      => '777ccc',
            'tstamp'   => '1238081389',
            'location' => '41.12,-71.34'
            ],
        ];


        $es->getIndex('work')->saveAll( $data);
    }

    public function test3()
    {
        $es = new Elastic();
        $data=[
            "username"=>"tim",
            "age"=>"18"
        ];
        $doc=$es->getIndex('hello')->search();
        dump($doc);
    }
}
