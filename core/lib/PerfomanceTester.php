<?php

namespace Zotto;

class PerfomanceTester {

    public static $metrics;

    public static function startPerfomanceTest()
    {
       // echo "Start perfomance test \n";
        self::$metrics['time']['start']=microtime(true);
        self::$metrics['memory']['start']=memory_get_usage(false);
    }

    public static function endPerfomanceTest()
    {
        //echo "End perfomance test \n";
        self::$metrics['time']['end']=microtime(true);
        self::$metrics['memory']['end']=memory_get_usage(true);
    }

    public static function getPerfomanceMetrics()
    {
        return array(
            'time'=>-self::calcMetricDelta('time'),
            'memory'=>-(self::calcMetricDelta('memory')/(1024*1024))."M"
        );
    }

    public static function calcMetricDelta($metricName){
        return self::$metrics[$metricName]['start']-self::$metrics[$metricName]['end'];
    }
} 