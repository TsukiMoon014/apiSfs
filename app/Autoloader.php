<?php

namespace apiSfs\app;

/**
 * Class Autoloader
 * @package apiSfs\app
 * Autoloading handler
 */
class Autoloader
{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class)
    {
        $parts = preg_split('#\\\#', $class);
        $className = array_pop($parts);
        switch ($className) {
            case 'Config':
            case 'ConfigInterface':
                require_once __DIR__.'/../core/ConfigHandler/'.$className.'.php';
                break;
            case 'StockInterface':
            case 'StockModel':
                require_once __DIR__.'/../src/Stock/'.$className.'.php';
                break;
            case 'GalleryInterface':
            case 'GalleryModel':
                require_once __DIR__.'/../src/Gallery/'.$className.'.php';
                break;
            case 'Connection':
            case 'ConnectionInterface':
            case 'AbstractConnection':
                require_once __DIR__.'/../core/Database/'.$className.'.php';
                break;
            case 'PackageException':
            case 'IPException':
            case 'EANException':
            case 'MaxmindException':
                require_once __DIR__.'/../core/Exceptions/'.$className.'.php';
                break;
            case 'PackageInterface':
            case 'PackageModel':
                require_once __DIR__.'/../src/Package/'.$className.'.php';
                break;
            case 'EANInterface':
            case 'EANHandler':
            case 'EANModel':
                require_once __DIR__.'/../src/EAN/'.$className.'.php';
                break;
            case 'RouteInterface':
            case 'RouteHandler':
                require_once __DIR__.'/../src/Route/'.$className.'.php';
                break;
            case 'Utils':
                require_once __DIR__.'/../src/Utils/'.$className.'.php';
                break;
            case 'MaxmindInterface':
            case 'MaxmindHandler':
                require_once __DIR__.'/../src/Maxmind/'.$className.'.php';
                break;
        }
    }
}