<?php

namespace apiSfs\src\EAN;

interface EANInterface
{
    /**
     * Transforms an EAN list separated by dashes (-) into array
     */
    public static function getEansFromString($eanList);

    /**
     * Transforms an array representing an EAN list into list of EANs separated by dashes (-)
     */
    public static function getEanString($eanArray);

    /**
     * Transforms an array representing an EAN list into array representing each EAN quantity
     */
    public static function getEansInfosFromEanArray($eanArray);

    /**
     * Transforms an EAN list separated by dashes (-) into array representing each EAN quantity
     */
    public static function getEansInfosFromEanString($eanString);
}