<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use apiSfs\core\Database\Connection;
use apiSfs\src\Colisweb\ColiswebHandler;
use apiSfs\src\Package\PackageModel;
use apiSfs\src\Route\RouteHandler;

require_once __DIR__.'/app/Autoloader.php';
require_once 'vendor/autoload.php';
require_once 'resources/Maxmind/geoip.inc';
require_once 'resources/Maxmind/geoipcity.inc';
require_once 'resources/Maxmind/geoipregionvars.php';

Autoloader::register();
Config::loadConfig();

//$routeHandler = new RouteHandler();
//$routeHandler
//    ->loadRoutes()
//    ->runRouter()
//;

//$packageModel = new PackageModel(Connection::getConnection());
$coliswebModel = new ColiswebHandler();
dump(COLISWEB['development']['base_url']);
dump($coliswebModel->getCarrierTiming('A90', '75003', '3662657208344'));
//$coliswebModel->getCarrierTiming('A90', '75003', '3662657208344');