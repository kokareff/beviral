<?php
/**
 * Файл инициализации библиотеки zotto3
 */

require_once __DIR__ . '/../vendor/autoload.php';

define('ZOTTO_ROOT', realpath(__DIR__ . '/../') . '/');
define('ZOTTO_TOOLS', realpath(__DIR__).'/tool/');

define('CORE_DIR', __DIR__ . '/');
define('CORE_LIB', __DIR__ . '/lib/');
define('CORE_ACTIONS', __DIR__ . '/lib/Actions/');
define('CORE_ETC', __DIR__ . '/etc/');
define('CORE_CONFIG', __DIR__ . '/config/');

date_default_timezone_set('Europe/Moscow');


/**
 * Регистрация автозагрузчика
 */
spl_autoload_register(
    function($className)
    {

        if (substr($className, 0, 6) !== 'Zotto\\') {
            return;
        }

        $subPath = substr($className, 6);
        $subPath = str_replace('\\', DIRECTORY_SEPARATOR, $subPath);

       // var_dump($subPath);

        $filePath = CORE_LIB . $subPath . '.php';

        if (file_exists($filePath)) {
            require $filePath;
        }
    }
);