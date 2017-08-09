<?php

namespace apiSfs\src\Route;

use apiSfs\core\Database\Connection;
use apiSfs\src\EAN\EANHandler;
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

    public function loadTestRoutes()
    {
        $this
            ->loadTestStockRoute()
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
                $eanHandler = new EANHandler(Connection::getConnection());
                $eanArray = $eanHandler->getEansFromString($request->getAttribute('eanString'));
                $ip = $request->getAttribute('ip');
                
                
            })
        ;

        return $this;
    }

    private function loadTestStockRoute()
    {
        $this
            ->app
            ->get(BASE_URL.'/test/stock', function (Request $request, Response $response) {
                $stockModel = new StockModel(Connection::getConnection());
                $stockInfos = $stockModel->getStockInfosByEan('A90', '3662657779813');
                $response = $response->withJson($stockInfos);
                
                return $response;
            })
        ;

        return $this;
    }

}