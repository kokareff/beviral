<?php

namespace PhpBase\Config;

use Zotto\Db\Adapter\MonDB;

class Mongo implements IAdapter {

    protected $_monDB;
    protected $_collectionName;

    protected static $_vars = array();

    public function __construct(MonDB $monDB, $collectionName){
        $this->_monDB = $monDB;
        $this->_collectionName = $collectionName;
    }

    /**
     * Возвращает значение по ключу
     *
     * @param string $key Ключ
     * @return mixed NULL если не найдено
     */
    public function get($key)
    {
        if(!isset(self::$_vars[$key])){
            self::$_vars = $this->getFullConfig();
        }
        return self::$_vars[$key];
    }

    /**
     * Устанавливает знаечение по ключу
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return bool
     */
    public function set($key, $value)
    {
        $fullConfig = $this->getFullConfig();
        if(!$fullConfig){
            $fullConfig = $this->_monDB->mongo->selectCollection($this->_collectionName)->insert(array($key=>$value));
        } else {
            $fullConfig = $this->_monDB->mongo->selectCollection($this->_collectionName)->
                                update(array('_id'=>new \MongoId($fullConfig['_id'])), array('$set' => array($key=>$value)),
                                array('multiple' => false));
        }

        return $fullConfig;
    }

    /**
     * Возвращает все известные ключи параметров
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->getFullConfig());
    }

    public function getFullConfig(){
       return $this->_monDB->mongo->selectCollection($this->_collectionName)->findOne();
    }
}