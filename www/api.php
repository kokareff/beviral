<?php
use PhpBase\Registry;

use Zotto\Application;
use Zotto\Actions\Factory;

require __DIR__ . '/../core/zotto.php';

define('ENVIRONMENT', isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'production');
define('BASE_DIR', __DIR__ . '/../');

$app = new Application();

$registry = Registry::getInstance();
$registry->app = $app;
$app->run(new \Zotto\Request\Api(), new Factory);
