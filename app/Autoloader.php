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
        }
    }
}