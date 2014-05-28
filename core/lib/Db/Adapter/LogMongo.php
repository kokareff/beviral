<?php

namespace Zotto\Db\Adapter;


use Zotto\Db\Adapter\Connection\MongoConnection;

class LogMongo extends  MongoConnection
{
    public function getDBName()
    {
        return 'log';
    }
}