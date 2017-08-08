<?php

namespace apiSfs\src\ean;

class EANHandler implements EANInterface
{
    public function getEansFromString($eanList)
    {
        return explode('-', $eanList);
    }
}