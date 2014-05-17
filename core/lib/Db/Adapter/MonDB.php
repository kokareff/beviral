<?php

namespace Zotto\Db\Adapter;
use PhpBase\Config\Files;

class MonDB {
    private  $connection=null;
    public  $mongo=null;
    private static $_config;

    private function __construct($connection, $db){
        $this->connection = new \MongoClient($connection);
        $this->mongo = $this->connection->selectDB($db);
    }
    private function __clone(){;}

    private static $instance=null;

    /**
     * @static
     * @return MonDB
     */
    public static function getInstance(){
        if(self::$instance === null){
            self::$_config = (new Files(CORE_CONFIG))->get('mongoDB');
            self::$instance = new MonDB(self::$_config['connection'], self::$_config['db']);
        }
        return self::$instance;
    }

    public static function toMongoId($data){
        $ret = array();
        foreach ($data as $val) {
            $ret[]=new \MongoId($val);
        }
        return $ret;
    }
}