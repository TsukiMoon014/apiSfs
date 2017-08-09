<?php

namespace apiSfs\src\EAN;

interface EANInterface
{
    public function getEansFromString($eanList);
}