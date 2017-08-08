<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use apiSfs\src\route\RouteHandler;

require_once __DIR__.'/app/Autoloader.php';
require 'vendor/autoload.php';

Autoloader::register();
Config::loadConfig();

$routeHandler = new RouteHandler();
$routeHandler
    ->loadRoutes()
    ->runRouter()
;