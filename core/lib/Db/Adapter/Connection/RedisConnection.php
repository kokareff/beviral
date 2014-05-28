<?php

namespace Zotto\Db\Adapter\Connection;

use Zotto\Config\Files;


/**
 * Class RedisConnection
 * @package Zotto\Db\Adapter\Connection
 *
 *
 * @method static \Redis getInstance()
 */
class RedisConnection extends ConnectionPool
{
    /**
     * @param $dbConfigName
     * @return \Redis
     * @throws \Exception
     */

    public static function  getConnection($dbConfigName)
    {

        $config = (new Files(CORE_CONFIG))->get('redis');
        $config = $config[$dbConfigName];

        if(empty($config)){
            throw new \Exception("Can't find config for redis '$dbConfigName'");
        }

        $connect = new \Redis();
        $connect->connect($config['host'], $config['port']);

        if (isset($config['password'])) {
            $connect->auth($config['password']);
        }

        if(isset($config['database'])){
            $connect->select($config['database']);
        }

        $connect->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);

        return $connect;
    }

    public function getDBName(){
        return 'default_redis';
    }



} 