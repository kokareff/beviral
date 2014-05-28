<?php

namespace Zotto\Db\Adapter;


use Zotto\Config\Files;

abstract class RedisConnection
{
    protected static $_instancePool = array();

    /**
     * @return \Redis
     */
    public static function getInstance()
    {
        if (self::$_instancePool == null) {
            self::$_instancePool = array();
        }
        /**
         * @var RedisConnection
         */
        $class = new static();
        $dbName = $class->getDBName();
        if(!isset(self::$_instancePool[$dbName])){
            self::$_instancePool[$dbName]=static::getConnection($dbName);
        }

        return self::$_instancePool[$dbName];
    }

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

    abstract public function getDBName();



} 