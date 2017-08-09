<?php

namespace apiSfs\core\ConfigHandler;

/**
 * Interface ConfigInterface
 *
 * Provides JSON configuration files loading logic
 *
 * @package apiSfs\core\ConfigHandler
 */
interface ConfigInterface
{

    /**
     * Loads configuration files
     */
    public static function loadConfig();
}