<?php

namespace apiSfs\src\stock;

interface StockInterface
{
    public function getStockInfosByEan($cegidID, $ean);
}