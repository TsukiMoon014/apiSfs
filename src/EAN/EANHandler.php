<?php

namespace apiSfs\src\EAN;

use apiSfs\core\Exceptions\EANException;

/**
 * Class EANHandler
 *
 * Provides EAN formatting methods
 *
 * @package apiSfs\src\EAN
 */
class EANHandler implements EANInterface
{
    /**
     * Transforms an EAN list separated by dashes (-) into array
     * @param $eanList
     * @return array
     * @throws EANException
     */
    public static function getEansFromString($eanList)
    {
        if (false === is_string($eanList)) {
            throw new EANException('Provided eanList is not string');
        } else {
            return explode('-', $eanList);
        }
    }

    /**
     * Transforms an array representing an EAN list into list of EANs separated by dashes (-)
     * @param $eanArray
     * @return string
     * @throws EANException
     */
    public static function getEanString($eanArray)
    {
        if (false === is_array($eanArray)) {
            throw new EANException('Provided eanArray is not Array');
        } else {
            return implode('-', $eanArray);
        }
    }

    /**
     * @param $eanArray
     * @return array
     * @throws EANException
     */
    public static function getEansInfosFromEanArray($eanArray)
    {
        if (false === is_array($eanArray)) {
            throw new EANException('Provided eanArray is not Array');
        } else {
            return array_count_values($eanArray);
        }
    }

    /**
     * @param $eanString
     * @return array
     * @throws EANException
     */
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