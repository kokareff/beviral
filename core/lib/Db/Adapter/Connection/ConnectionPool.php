<?php

namespace Zotto\Db\Adapter\Connection;

use Zotto\Config\Files;

abstract class ConnectionPool
{
    protected static $_instancePool = array();

    public static function getInstance()
    {
        if (self::$_instancePool == null) {
            self::$_instancePool = array();
        }
        $class = new static();
        $dbName = $class->getDBName();
        if(!isset(self::$_instancePool[$dbName])){
            self::$_instancePool[$dbName]=static::getConnection($dbName);
        }

        return self::$_instancePool[$dbName];
    }


    public static function  getConnection($dbConfigName){

    }
    abstract public function getDBName();



} 