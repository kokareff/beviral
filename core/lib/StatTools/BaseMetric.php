<?php

namespace Zotto\StatTools;


use Zotto\Db\Adapter\StatMongo;

abstract class BaseMetric
{
    abstract public function getMinTime();

    public function makeIndex(){
        $col = $this->getCollection();
        $col->ensureIndex(array('value.timeStart'=>1));
        $col->ensureIndex(array('value.timeStart'=>1, 'value.timeEnd'=>-1));
    }

    /**
     * @return \MongoCollection
     */
    public function getCollection(){
        return StatMongo::getInstance()->{'stat_'.$this->getName()};
    }

    public function write($params, $timeStart, $timeEnd)
    {
        $sm = new StatModel();
        $val = $this->getValue($params, $timeStart, $timeEnd);

        if (!empty($val)) {
            $sm->writeMetric($this->getName(), $val, $timeStart, $timeEnd);
        }
    }

    /**
     * @param $metricName
     * @return BaseMetric
     */
    public static function createMetric($metricName){
        $metric = 'Zotto\\StatTools\\Metric\\'.$metricName;
        /**
         * @var BaseMetric $metric
         */
       return new $metric();
    }

    abstract public function getValue($params, $timeStart, $timeEnd);

    public function getName()
    {
        $ar = explode('\\', get_class($this));
        return end($ar);
    }


    /**
     * @param $sumField
     * @param $groupField
     * @param $timeStart
     * @param $timeEnd
     * @return \MongoCursor
     */
    public function getSum($sumField, $groupField, $timeStart, $timeEnd){
        $sm = new StatModel();
        $pipeline = [
          //  ['$project'=>[$sumField=>1, $groupField=>1, 'res'=>1]],
            [ '$match' =>
                ['value.timeStart' =>
                    ['$gte' => $timeStart, '$lt'=>$timeEnd-1]]
            ],
            ['$group' => [
              '_id' => '$value.'.$groupField,
              'res' => ['$sum' => '$value.'.$sumField  ]],
            ]
            ,
            ['$sort' => ['res' => -1]]
        ];

       // var_dump($pipeline); die();
        return $sm->getMetricCollection($this->getName())
                        ->aggregate($pipeline, ['allowDiskUse'=>true])['result'];
    }
} 