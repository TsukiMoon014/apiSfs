<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use apiSfs\src\route\RouteHandler;

require_once __DIR__.'/app/Autoloader.php';
require 'vendor/autoload.php';
require 'resources/Maxmind/geoip.inc';
require 'resources/Maxmind/geoipcity.inc';
require 'resources/Maxmind/geoipregionvars.php';

Autoloader::register();
Config::loadConfig();

$routeHandler = new RouteHandler();
$routeHandler
    ->loadTestRoutes()
    ->runRouter()
;