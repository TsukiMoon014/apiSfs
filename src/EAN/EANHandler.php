<?php

namespace apiSfs\src\EAN;

class EANHandler implements EANInterface
{
    public function getEansFromString($eanList)
    {
        return explode('-', $eanList);
    }
}