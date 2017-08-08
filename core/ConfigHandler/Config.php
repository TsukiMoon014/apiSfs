<?php

namespace apiSfs\core\ConfigHandler;

class Config implements ConfigInterface
{
    public static function loadConfig()
    {
        foreach (glob(__DIR__.'/../../app/config/*.json') as $configFile) {
            $fileContent = json_decode(
                file_get_contents($configFile)
            );
            var_dump($fileContent);
        }
    }
}