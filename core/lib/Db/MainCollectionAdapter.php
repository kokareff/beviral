<?php

namespace Zotto\Db;

use Zotto\Db\Adapter\LogMongo;
use Zotto\Db\Adapter\MainMongo;
use Zotto\Db\Adapter\QupaMongo;
use Zotto\Db\Adapter\StatMongo;

class MainCollectionAdapter extends  CollectionAdapter
{
    public function getDefaultConnection(){
        return MainMongo::getInstance();
    }
}
