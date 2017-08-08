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
    }
}