<?php

namespace apiSfs\src\Route;

use apiSfs\core\Database\Connection;
use apiSfs\core\Exceptions\EANException;
use apiSfs\core\Exceptions\GalleryException;
use apiSfs\core\Exceptions\IPException;
use apiSfs\core\Exceptions\MaxmindException;
use apiSfs\src\EAN\EANHandler;
use apiSfs\src\EAN\EANModel;
use apiSfs\src\Gallery\GalleryModel;
use apiSfs\src\Maxmind\MaxmindHandler;
use apiSfs\src\Stock\StockModel;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class RouteHandler implements RouteInterface
{
    private $app;

    public function __construct()
    {
        $this->app = new App();
    }

    public function loadRoutes()
    {
        $this
            ->loadStockRoute()
        ;

        return $this;
    }

    public function runRouter()
    {
        $this
            ->app
            ->run()
        ;

        return $this;
    }

    private function loadStockRoute()
    {
        $this
            ->app
            ->get(BASE_URL.'/galleryList/{eanString}/{ip}', function (Request $request, Response $response) {
                $eanString = $request->getAttribute('eanString');
                $ip = $request->getAttribute('ip');

                $masterEans = array();
                $eanModel = new EANModel(Connection::getConnection());

                try {
                    $eanArray = EANHandler::getEansFromString($eanString);
                } catch (EANException $exception) {
                    $response = $response->withJson(
                        array("ERROR", $exception->getMessage())
                    );

                    return $response;
                }

                foreach ($eanArray as $ean) {
                    try {
                        array_push($masterEans, $eanModel->getMasterEan($ean));
                    } catch (EANException $exception) {
                        $response = $response->withJson(
                            array("ERROR", $exception->getMessage())
                        );

                        return $response;
                    }
                }

                $maxmindHandler = new MaxmindHandler();
                try {
                    $ipInfos = $maxmindHandler->getIpInfos($ip);
                } catch (IPException $exception) {
                    $response = $response->withJson(
                        array("ERROR", $exception->getMessage())
                    );

                    return $response;
                } catch (MaxmindException $exception) {
                    $response = $response->withJson(
                        array("ERROR", $exception->getMessage())
                    );

                    return $response;
                }

                $galleryModel = new GalleryModel(Connection::getConnection());
                try {
                    $closeGaleries = $galleryModel->getCloseGalleryList($ipInfos['latitude'], $ipInfos['longitude']);
                } catch (GalleryException $exception) {
                    $response = $response->withJson(
                        array("ERROR", $exception->getMessage())
                    );

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
                        'perimeter' => MAX_PERIMETER
                    );
                }
                
                $response = $response->withJson($res);

                return $response;
            })
        ;

        return $this;
    }
}