<?php

namespace apiSfs\src\Utils;

use apiSfs\core\Exceptions\IPException;

class Utils
{
    public static function isValidIpAddress($ip)
    {
        if (false === filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new IPException();
        } else {
            return true;
        }
    }

    public static function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $dist *= 60 * 1.1515 * 609344;

        return $dist;
    }
}