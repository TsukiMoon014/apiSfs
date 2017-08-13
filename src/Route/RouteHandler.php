<?php

namespace apiSfs\src\Route;

use apiSfs\core\Database\Connection;
use apiSfs\core\Exceptions\ColiswebException;
use apiSfs\core\Exceptions\EANException;
use apiSfs\core\Exceptions\GalleryException;
use apiSfs\core\Exceptions\IPException;
use apiSfs\core\Exceptions\MaxmindException;
use apiSfs\src\Colisweb\ColiswebHandler;
use apiSfs\src\EAN\EANHandler;
use apiSfs\src\EAN\EANModel;
use apiSfs\src\Gallery\GalleryModel;
use apiSfs\src\Maxmind\MaxmindHandler;
use apiSfs\src\Stock\StockModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class RouteHandler implements RouteInterface
{
    private $app;
    private $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->container['notFoundHandler'] = function ($c) {
            return function (ServerRequestInterface $request, ResponseInterface $response) use ($c) {
                $resArray = array(
                    'status' => 'ERROR',
                    'message' => 'Provided URL does not match any route'
                );
                return $this->container['response']
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write(json_encode($resArray));
            };
        };
        $this->app = new App($this->container);
    }

    public function loadRoutes()
    {
        $this
            ->loadStockRoute()
            ->loadCarrierTimingRoute()
        ;

        return $this;
    }

    public function runRouter()
    {
        $this->app->run();

        return $this;
    }

    private function loadStockRoute()
    {
        $this
            ->app
            ->get(URL_PREFIX_BASE_URL.'/galleryList/{eanString}/{ip}', function (Request $request, Response $response) {
                $eanString = $request->getAttribute('eanString');
                $ip = $request->getAttribute('ip');

                $masterEans = array();
                $eanModel = new EANModel(Connection::getConnection());

                try {
                    $eanArray = EANHandler::getEansFromString($eanString);
                } catch (EANException $exception) {
                    $response = $response
                        ->withStatus(500)
                        ->withJson(array("ERROR", $exception->getMessage()))
                    ;

                    return $response;
                }

                foreach ($eanArray as $ean) {
                    try {
                        array_push($masterEans, $eanModel->getMasterEan($ean));
                    } catch (EANException $exception) {
                        $response = $response
                            ->withStatus(500)
                            ->withJson(array("ERROR", $exception->getMessage()))
                        ;

                        return $response;
                    }
                }

                $maxmindHandler = new MaxmindHandler();
                try {
                    $ipInfos = $maxmindHandler->getIpInfos($ip);
                } catch (IPException $exception) {
                    $response = $response
                        ->withStatus(500)
                        ->withJson(array("ERROR", $exception->getMessage()))
                    ;

                    return $response;
                } catch (MaxmindException $exception) {
                    $response = $response
                        ->withStatus(500)
                        ->withJson(array("ERROR", $exception->getMessage()))
                    ;

                    return $response;
                }

                $galleryModel = new GalleryModel(Connection::getConnection());
                try {
                    $closeGaleries = $galleryModel->getCloseGalleryList($ipInfos['latitude'], $ipInfos['longitude']);
                } catch (GalleryException $exception) {
                    $response = $response
                        ->withStatus(500)
                        ->withJson(array("ERROR", $exception->getMessage()))
                    ;

                    return $response;
                }

                $stockModel = new StockModel(Connection::getConnection());
                $galeriesHavingStock = array();
                foreach ($closeGaleries as $gallery) {
                    $stockRequest = $stockModel->isStockAvailable($gallery, $eanString);
                    if (false !== $stockRequest) {
                        $galeriesHavingStock[$gallery] = $stockRequest;
                    }
                }

                if (!empty($galeriesHavingStock)) {
                    $res = array(
                        'status' => 'SUCCESS',
                        'results' => $galeriesHavingStock
                    );
                } else {
                    $res = array(
                        'status' => 'ERROR',
                        'message' => 'Stock unavailable close to provided IP',
                        'coordinates' => array(
                            'latitude' => $ipInfos['latitude'],
                            'longitude' => $ipInfos['longitude']
                        ),
                        'perimeter' => PERIMETER_MAX_PERIMETER
                    );
                }

                $response = $response
                    ->withStatus(200)
                    ->withJson($res)
                ;

                return $response;
            })
        ;

        return $this;
    }

    private function loadCarrierTimingRoute()
    {
        $this
            ->app
            ->get(URL_PREFIX_BASE_URL.'/carrier/timing/{cegidID}/{postalCode}/{eanString}', function (Request $request, Response $response) {
                $cegidID = $request->getAttribute('cegidID');
                $postalCode = $request->getAttribute('postalCode');
                $eanString = $request->getAttribute('eanString');

                $coliswebHandler = new ColiswebHandler(Connection::getConnection());
                try {
                    $res = $coliswebHandler->getCarrierTiming($cegidID, $postalCode, $eanString);
                } catch (ColiswebException $exception) {
                    $response = $response
                        ->withStatus(500)
                        ->withJson(array("ERROR", $exception->getMessage()))
                    ;

                    return $response;
                }

                $response = $response
                    ->withStatus(200)
                    ->withJson($res)
                ;

                return $response;
            })
        ;
    }
}