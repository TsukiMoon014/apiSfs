<?php

namespace apiSfs\core\ConfigHandler;

class Config implements ConfigInterface
{
    public static function loadConfig()
    {
        $databaseConfig = json_decode(
            file_get_contents(__DIR__.'/../../app/config/database.json')
        );
        $databaseArray = array();
        foreach ($databaseConfig->database as $key => $value) {
            $databaseArray[$key] = $value;
        }
        define('DATABASE', $databaseArray);

        $urlPrefixConfig = json_decode(
            file_get_contents(__DIR__.'/../../app/config/urlPrefix.json')
        );
        $urlPrefix = $urlPrefixConfig
            ->url_prefix
            ->base_url
        ;
        define('BASE_URL', $urlPrefix);

        $perimeterConfig = json_decode(
            file_get_contents(__DIR__.'/../../app/config/perimeter.json')
        );
        $perimeter = $perimeterConfig
            ->perimeter
            ->max_perimeter
        ;
        define('MAX_PERIMETER', $perimeter);
    }
}