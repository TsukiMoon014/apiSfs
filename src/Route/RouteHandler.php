<?php

namespace apiSfs\src\route;

use apiSfs\core\database\Connection;
use apiSfs\src\ean\EANHandler;
use apiSfs\src\stock\StockModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

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
            ->get(BASE_URL.'galleryList/{eanString}/{ip}', function (Request $request, Response $response) {
                $eanHandler = new EANHandler(Connection::getConnection());
                $eanArray = $eanHandler->getEansFromString($request->getAttribute('eanString'));
                $ip = $request->getAttribute('ip');
                $stockModel = new StockModel(Connection::getConnection());

                $resArray = array();
                foreach ($eanArray as $ean) {
                    array_push($resArray, $stockModel->getStockInfosByEan('A90', $ean));
                }

                $response
                    ->getBody()
                    ->write($ip)
                ;

                return $response;
            })
        ;

        return $this;
    }

}