<?php

namespace Zotto\StatTools;

use Zotto\Db\Adapter\StatMongo;

class StatModel {
    /**
     * @param $metricName
     * @return \MongoCollection
     */
    public function getMetricCollection($metricName){
        return StatMongo::getInstance()->{'stat_'.$metricName};
    }

    public function writeMetric($metricName, $metricData, $timeStart, $timeEnd){
        $metricData = array_map( function($el) use ($timeStart, $timeEnd){
            return array_merge($el, array('timeStart'=>$timeStart, 'timeEnd'=>$timeEnd));
        } , $metricData);
        $this->getMetricCollection($metricName)->batchInsert($metricData);
    }

    public static function getMetricList(){
        $data = scandir(realpath(__DIR__).'/Metric');
        unset($data[0]);
        unset($data[1]);
        foreach ($data as &$taskName) {
            $taskName = substr($taskName, 0, strlen($taskName) - 4);
        }
        return $data;
    }


} 