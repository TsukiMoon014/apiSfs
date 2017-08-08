<?php

namespace apiSfs\src\package;

use apiSfs\core\database\AbstractConnection;

class PackageModel extends AbstractConnection implements PackageInterface
{
    public function getPackageDescriptionByEan($ean)
    {
        $req = $this
            ->connection
            ->prepare('
              SELECT
                ROUND(FPL.width * 100, 2) AS width,
                ROUND(FPL.height * 100, 2) AS height,
                ROUND(FPL.depth * 100, 2) AS length,
                ROUND(FPL.weight / 1000, 2) AS weight
              FROM final_product_logistic FPL
              LEFT JOIN final_product FP
              ON FPL.product_id = FP.product_id
              WHERE FP.ean = ?
             ')
        ;

        $req->execute(array($ean));

        return $req->fetch();
    }
}