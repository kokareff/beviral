<?php
if (ENVIRONMENT == 'dev') {
    // настройки для разработчика
    return array('quant' => 3600);
} else {
    // продакшен
    return array('quant' => 3600);
}
 