<?php

namespace Zotto\Db\Adapter\Connection;

use Zotto\Config\Files;

/**
 * Class MongoConnection
 * @package Zotto\Db\Adapter\Connection
 *
 * @method static \MongoDB getInstance()
 */
class MongoConnection extends ConnectionPool
{

    public static function  getConnection($dbConfigName)
    {

        $config = (new Files(CORE_CONFIG))->get('mongo');
        $config = $config[$dbConfigName];

        if (empty($config)) {
            throw new \Exception("Can't find config for mongo '$dbConfigName'");
        }

        $connect = new \MongoClient($config['connection']);
        $connect = $connect->selectDB($config['db']);


        return $connect;
    }

    public function getDBName()
    {
        return 'default_mongo';
    }
}