<?php

namespace apiSfs\src\Maxmind;

use apiSfs\core\Exceptions\MaxmindException;
use apiSfs\src\Utils\Utils;

class MaxmindHandler implements MaxmindInterface
{
    public function getIpInfos($ip)
    {
        if (true === Utils::isValidIpAddress($ip)) {
            $handler = geoip_open(__DIR__.'/../../resources/Maxmind/GeoLiteCity.dat', GEOIP_STANDARD);
            $res = geoip_record_by_addr($handler, $ip);

            if (isset($res) && !empty($res)) {
                return array(
                    'status' => 'SUCCESS',
                    'latitude' => $res->latitude,
                    'longitude' => $res->longitude,
                );
            } else {
                throw new MaxmindException('Maxmind could not retrieve provided IP infos');
            }
        } else {
            return false;
        }
    }
}
