<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

require_once __DIR__.'/app/Autoloader.php';
require 'vendor/autoload.php';

Autoloader::register();
Config::loadConfig();

$app = new App();

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();