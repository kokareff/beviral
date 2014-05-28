<?php

namespace Zotto\StatTools\Metric;

use Zotto\Db\Adapter\Connection\MongoConnection;
use Zotto\Db\Adapter\DmpMongo;
use Zotto\Db\Adapter\StatMongo;
use Zotto\Model\Collection\VisitedPagesCollection;
use Zotto\StatTools\BaseMetric;
use Zotto\Task\TaskLog;

class Domain extends BaseMetric
{
    public function getValue($params, $timeStart, $timeEnd)
    {
        $resultCol = 'stat_Domain';

        $map = new \MongoCode("function(){" .<<<JS
            emit({domain: this['domain'],timeStart:'$timeStart'},
                {
                'count':1,
                'domain':this['domain'],
                'timeStart':$timeStart,
                'timeEnd':$timeEnd
                })
JS
            . "}");
        $reduce = new \MongoCode("function(key, values){" .<<<JS
            var sum = 0;
            var domain = '';
            values.forEach(function(val){
                sum+=val['count'];
            });
            return {
                'count':sum,
                'domain':key.domain,
                'timeStart':$timeStart,
                'timeEnd':$timeEnd
                };
JS
            . "}");

        $command =  [
            "mapreduce" => "visitedPages",
            "map" => $map,
            "reduce" => $reduce,
            "query" => ['time'=>[
                '$gte'=>$timeStart,
                '$lte'=>$timeEnd
            ]],
            "out" => [
                "merge" => $resultCol,
                "db"=>"stat"
            ]];

        //TaskLog::log('stat_domain',print_r($command, true));

        DmpMongo::getInstance()->command($command);


    }

    public function getMinTime()
    {
        $vp = new VisitedPagesCollection();
        $min = $vp->findBy(array('time' => array('$exists' => true)))->sort(array('time' => 1))->limit(1)->getNext();

        return $min['time'];
    }
}