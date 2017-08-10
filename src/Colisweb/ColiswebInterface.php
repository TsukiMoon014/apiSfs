<?php

namespace apiSfs\src\Colisweb;

/**
 * Interface ColiswebInterface
 *
 * Provides Colisweb's API interactions logic
 *
 * @package apiSfs\src\Colisweb
 */
interface ColiswebInterface
{
    /*
     * Gets available Colisweb timing
     */
    public function getCarrierTiming($cegidID, $postalCode, $packageList);
}