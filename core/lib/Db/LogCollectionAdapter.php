<?php

namespace Zotto\Db;

use Zotto\Db\Adapter\LogMongo;
use Zotto\Db\Adapter\StatMongo;

class LogCollectionAdapter extends  CollectionAdapter
{
    public function getDefaultConnection(){
        return LogMongo::getInstance();
    }
}
