<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use apiSfs\core\Database\Connection;
use apiSfs\core\Exceptions\EANException;
use apiSfs\core\Exceptions\GalleryException;
use apiSfs\core\Exceptions\IPException;
use apiSfs\src\EAN\EANHandler;
use apiSfs\src\EAN\EANModel;
use apiSfs\src\Gallery\GalleryModel;
use apiSfs\src\Maxmind\MaxmindHandler;
use apiSfs\src\route\RouteHandler;
use apiSfs\src\Stock\StockModel;

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


//$eanString = '3662657780093';
//$ip = '83.202.54.183';
//
//$masterEans = array();
//$eanModel = new EANModel(Connection::getConnection());
//
//try {
//    $eanArray = EANHandler::getEansFromString($eanString);
//} catch (EANException $exception) {
//    $response = $response->withJson(
//        array("ERROR", $exception->getMessage())
//    );
//
//    return $response;
//}
//
//foreach ($eanArray as $ean) {
//    try {
//        array_push($masterEans, $eanModel->getMasterEan($ean));
//    } catch (EANException $exception) {
//        $response = $response->withJson(
//            array("ERROR", $exception->getMessage())
//        );
//
//        return $response;
//    }
//}
//
//$maxmindHandler = new MaxmindHandler();
//try {
//    $ipInfos = $maxmindHandler->getIpInfos($ip);
//} catch (IPException $exception) {
//    $response = $response->withJson(
//        array("ERROR", $exception->getMessage())
//    );
//
//    return $response;
//} catch (MaxmindException $exception) {
//    $response = $response->withJson(
//        array("ERROR", $exception->getMessage())
//    );
//
//    return $response;
//}
//
//$galleryModel = new GalleryModel(Connection::getConnection());
//try {
//    $closeGaleries = $galleryModel->getCloseGalleryList($ipInfos['latitude'], $ipInfos['longitude']);
//} catch (GalleryException $exception) {
//    $response = $response->withJson(
//        array("ERROR", $exception->getMessage())
//    );
//
//    return $response;
//}
//
//$stockModel = new StockModel(Connection::getConnection());
//$galeriesHavingStock = array();
//foreach ($closeGaleries as $gallery) {
//    dump($gallery);
//    dump($eanString);
//    $stockRequest = $stockModel->isStockAvailable($gallery, $eanString);
//    dump($stockRequest);
//    if (false !== $stockRequest) {
//        $galeriesHavingStock[$gallery] = $stockRequest;
//    }
//}