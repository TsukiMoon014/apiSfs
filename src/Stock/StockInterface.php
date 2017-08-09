<?php

namespace apiSfs\src\Stock;

interface StockInterface
{
    public function getStockInfosByEan($cegidID, $ean);
}