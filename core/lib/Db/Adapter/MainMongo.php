<?php

namespace Zotto\Db\Adapter;


use Zotto\Db\Adapter\Connection\MongoConnection;

class MainMongo extends  MongoConnection
{
    public function getDBName()
    {
        return 'main';
    }
}