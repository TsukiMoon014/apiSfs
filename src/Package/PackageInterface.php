<?php

namespace apiSfs\src\Package;

interface PackageInterface
{
    public function getPackageDescriptionByEan($ean);
}