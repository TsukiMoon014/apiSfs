<?php

namespace apiSfs\src\EAN;

class EANHandler implements EANInterface
{
    public function getEansFromString($eanList)
    {
        $eanArray = explode('-', $eanList);

        return array_count_values($eanArray);
    }
}