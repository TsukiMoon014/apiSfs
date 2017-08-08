<?php

namespace apiSfs\src\route;

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
            ->loadDefaultRoute()
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

    private function loadDefaultRoute()
    {
        $this
            ->app
            ->get('/hello/{name}', function (Request $request, Response $response) {
                $name = $request->getAttribute('name');
                $response->getBody()->write("Hello, $name");

                return $response;
            })
        ;

        return $this;
    }

    private function loadStockRoute()
    {
        $this
            ->app
            ->get('/hello/{name}', function (Request $request, Response $response) {
                $name = $request->getAttribute('name');
                $response->getBody()->write("Hello, $name");

                return $response;
            })
        ;

        return $this;
    }

}