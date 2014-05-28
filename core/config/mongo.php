<?php
/**
 * MongoDb config
 */
if(ENVIRONMENT=='dev'){
    // настройки для разработчика
    return [
        'main'=>[
            'connection'=>'mongodb://91.228.153.82:27017',
            'db'=>'BeSeed_Main'
        ],
        'log'=>[
            'connection'=>'mongodb://91.228.153.82:27017',
            'db'=>'BeSeed_Log'
        ],
        'stat'=>[
            'connection'=>'mongodb://91.228.153.82:27017',
            'db'=>'BeSeed_Stat'
        ]
    ];
} else {
    // продакшен
    return [
        'main'=>[
            'connection'=>'mongodb://91.228.153.82:27017',
            'db'=>'BeSeed_Main'
        ],
        'log'=>[
            'connection'=>'mongodb://91.228.153.82:27017',
            'db'=>'BeSeed_Log'
        ],
        'stat'=>[
            'connection'=>'mongodb://91.228.153.82:27017',
            'db'=>'BeSeed_Stat'
        ]
    ];
}
 