<?php

namespace Zotto\Db\Adapter;

use PhpBase\Config\Files;

class Redis extends RedisConnection
{

    public static function getHashArray($name)
    {
        $array = static::getInstance()->hGetAll($name);
        return $array;
    }

    public static function setHashArray($name, array $values)
    {
        foreach ($values as $key => $value) {
            static::getInstance()->hSet($name, $key, $value);
        }

    }

    public function getDBName()
    {
        return 'qupaCache';
    }
}