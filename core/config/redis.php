<?php
if (ENVIRONMENT == 'dev') {
    // настройки для разработчика
    return [
        'cache' => [
            'host' => 'localhost'
            , 'port' => 6379
            , 'database' => 2
        ]
    ];
} else {
    // продакшен
    // настройки для разработчика
    return [
        'cache' => [
            'host' => 'localhost'
            , 'port' => 6379
            , 'database' => 2
        ]
    ];
}
 