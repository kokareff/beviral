<?php
if (ENVIRONMENT == 'dev') {
    // настройки для разработчика
    return array('dir' => CORE_LIB.'Task/Tasks/', 'cli'=>ZOTTO_TOOLS);
} else {
    // продакшен
    return array('dir' => CORE_LIB.'Task/Tasks/', 'cli'=>ZOTTO_TOOLS);
}