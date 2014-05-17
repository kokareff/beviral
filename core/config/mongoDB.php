<?php
/**
 * MongoDb config
 */
if(ENVIRONMENT=='dev'){
    // настройки для разработчика
    return array('connection'=>'mongodb://localhost:27017',
        'db'=>'dmp');
} else {
    // продакшен
    return array('connection'=>'mongodb://localhost:27017',
                 'db'=>'dmp');
}
 