<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use apiSfs\core\Database\Connection;
use apiSfs\src\EAN\EANHandler;
use apiSfs\src\EAN\EANModel;
use apiSfs\src\Gallery\GalleryModel;
use apiSfs\src\route\RouteHandler;
use apiSfs\src\Stock\StockModel;

require_once __DIR__.'/app/Autoloader.php';
require 'vendor/autoload.php';
require 'resources/Maxmind/geoip.inc';
require 'resources/Maxmind/geoipcity.inc';
require 'resources/Maxmind/geoipregionvars.php';

Autoloader::register();
Config::loadConfig();

//$routeHandler = new RouteHandler();
//$routeHandler
//    ->loadTestRoutes()
//    ->runRouter()
//;

$stockModel = new StockModel(Connection::getConnection());

dump($stockModel->isStockAvailable('A90', '3662657096200'));
$stockModel->isStockAvailable('A90', '3662657096200');