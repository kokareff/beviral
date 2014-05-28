<?php

namespace Zotto\Db;

use Zotto\Db\Adapter\StatMongo;

class StatCollectionAdapter extends  CollectionAdapter
{
    public function getDefaultConnection(){
        return StatMongo::getInstance();
    }
}
