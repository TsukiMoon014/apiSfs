<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use apiSfs\src\Route\RouteHandler;

require_once __DIR__.'/app/Autoloader.php';
require_once 'vendor/autoload.php';
require_once 'resources/Maxmind/geoip.inc';
require_once 'resources/Maxmind/geoipcity.inc';
require_once 'resources/Maxmind/geoipregionvars.php';

Autoloader::register();
Config::loadConfig();

$routeHandler = new RouteHandler();
$routeHandler
    ->loadRoutes()
    ->runRouter()
;