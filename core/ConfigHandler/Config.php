<?php

namespace apiSfs\core\ConfigHandler;

/**
 * Class Config
 *
 * Provides JSON configuration files loading logic
 *
 * @package apiSfs\core\ConfigHandler
 */
class Config implements ConfigInterface
{
    public static function loadConfig()
    {
        $configDirectory = scandir(__DIR__.'/../../app/config/');

        foreach ($configDirectory as $configFile) {
            if (false === is_file(__DIR__.'/../../app/config/'.$configFile)) {
                continue;
            }

            $config = json_decode(
                file_get_contents(__DIR__.'/../../app/config/'.$configFile),
                true
            );

            $configName = strtolower(
                preg_replace(
                    '/([a-z])([A-Z])/',
                    '$1_$2',
                    basename($configFile, '.json')
                )
            );

            foreach ($config[$configName] as $mainKey => $mainValue) {
                if (false === is_array($mainValue)) {
                    define(
                        strtoupper($configName.'_'.$mainKey),
                        $mainValue
                    );
                } else {
                    foreach ($mainValue as $key => $value) {
                        define(
                            strtoupper($configName.'_'.$mainKey.'_'.$key),
                            $value
                        );
                    }
                }
            }
        }
    }
}
