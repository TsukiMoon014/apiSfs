<?php

namespace apiSfs\src\EAN;

use apiSfs\core\Exceptions\EANException;

class EANHandler implements EANInterface
{
    public static function getEansFromString($eanList)
    {
        if (false === is_string($eanList)) {
            throw new EANException('Provided eanList is not string');
        } else {
            return explode('-', $eanList);
        }
    }

    public static function getEanString($eanArray)
    {
        if (false === is_array($eanArray)) {
            throw new EANException('Provided eanArray is not Array');
        } else {
            return implode('-', $eanArray);
        }
    }

    public static function getEansInfosFromEanArray($eanArray)
    {
        if (false === is_array($eanArray)) {
            throw new EANException('Provided eanArray is not Array');
        } else {
            return array_count_values($eanArray);
        }
    }

    public static function getEansInfosFromEanString($eanString)
    {
        if (false === is_string($eanString)) {
            throw new EANException('Provided eanList is not string');
        } else {
            $eanArray = self::getEansFromString($eanString);
            return array_count_values($eanArray);
        }
    }
}