<?php

namespace apiSfs;

use apiSfs\app\Autoloader;
use apiSfs\core\ConfigHandler\Config;

require_once __DIR__.'/app/Autoloader.php';

Autoloader::register();
Config::loadConfig();

var_dump(DATABASE);