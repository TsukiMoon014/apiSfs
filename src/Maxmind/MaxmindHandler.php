<?php

namespace apiSfs\src\Maxmind;

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
                    'lat' => $res->latitude,
                    'lng' => $res->longitude,
                    'postal_code' => $res->postal_code,
                    'city' => $res->city,
                    'country_code' => $res->country_code,
                );
            } else {
                return array(
                    'status' => "ERROR",
                    'result' => "Empty set"
                );
            }
        } else {
            return false;
        }
    }
}
