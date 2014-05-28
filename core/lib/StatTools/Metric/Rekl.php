<?php

namespace Zotto\StatTools\Metric;

use Zotto\Db\Adapter\DmpMongo;
use Zotto\Model\Collection\VisitedPagesCollection;
use Zotto\StatTools\BaseMetric;

class Rekl extends BaseMetric {
    public function getValue($params, $timeStart, $timeEnd)
    {

        $resultCol = 'stat_Rekl';

        $map = new \MongoCode("function(){" .<<<JS
            emit({refId: this['refId'],timeStart:'$timeStart'},
                {
                'count':1,
                'refId':this['refId'],
                'timeStart':$timeStart,
                'timeEnd':$timeEnd
                })
JS
            . "}");
        $reduce = new \MongoCode("function(key, values){" .<<<JS
            var sum = 0;
            values.forEach(function(val){
                sum+=val['count'];
            });
            return {
                'count':sum,
                'refId':key.refId,
                'timeStart':$timeStart,
                'timeEnd':$timeEnd
                };
JS
            . "}");

        $command =  [
            "mapreduce" => "visitedPages",
            "map" => $map,
            "reduce" => $reduce,
            "query" => [
                'time'=>[
                    '$gte'=>$timeStart,
                    '$lte'=>$timeEnd
                ]
                ,'refId'=>['$exists'=>true]
            ],
            "out" => [
                "merge" => $resultCol,
                "db"=>"stat"
            ]];


        DmpMongo::getInstance()->command($command);

    }

    public function getMinTime()
    {
        $vp = new VisitedPagesCollection();
        $min = $vp->findBy(array('time'=>array('$exists'=>true)))->sort(array('time'=>1))->limit(1)->getNext();

        return $min['time'];
    }
}