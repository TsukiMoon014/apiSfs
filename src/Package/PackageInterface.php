<?php

namespace apiSfs\src\package;

interface PackageInterface
{
    public function getPackageDescriptionByEan($ean);
}