<?php

namespace Zotto\Db\Adapter;

use Zotto\Db\Adapter\Connection\MongoConnection;

class StatMongo extends  MongoConnection {
    public function getDBName()
    {
        return 'stat';
    }
} 