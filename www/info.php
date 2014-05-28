<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/zotto.php';

define('ENVIRONMENT', isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'production');
define('BASE_DIR', __DIR__ . '/../');


phpinfo();