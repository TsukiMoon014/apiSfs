<?php

namespace apiSfs\src\ean;

interface EANInterface
{
    public function getEansFromString($eanList);
}