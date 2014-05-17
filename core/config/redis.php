<?php
if (ENVIRONMENT == 'dev') {
    // настройки для разработчика
    return array(
        'qupaCache' => array(
            'host' => '91.228.153.82'
        , 'port' => 6379
        , 'database' => 2
        , 'password' => 'qupaTheBest'
        ),
        'tokenCache' => array(
            'host' => '91.228.153.82'
        , 'port' => 6379
        , 'database' => 4
        , 'password' => 'qupaTheBest'
        )
    );
} else {
    // продакшен
    return array(
        'qupaCache' => array(
            'host' => '91.228.153.82'
        , 'port' => 6379
        , 'database' => 2
        , 'password' => 'qupaTheBest'
        , 'token_db' => 4
        ),
        'tokenCache' => array(
            'host' => '91.228.153.82'
        , 'port' => 6379
        , 'database' => 4
        , 'password' => 'qupaTheBest'
        )
    );
}
 