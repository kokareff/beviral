<?php
use Zotto\Model\Collection\UserInfoCollection;
use Zotto\Model\Collection\VisitedPagesCollection;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/core.php';

define('ENVIRONMENT', isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'production');
define('BASE_DIR', __DIR__ . '/../');


