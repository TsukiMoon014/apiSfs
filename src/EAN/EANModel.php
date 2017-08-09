<?php

namespace apiSfs\src\EAN;

use apiSfs\core\Database\AbstractConnection;
use apiSfs\core\Exceptions\EANException;

class EANModel extends AbstractConnection
{
    public function getMasterEan($ean)
    {
        $req = $this
            ->connection
            ->prepare('
                SELECT 
                  FP.family_id,
                  FP.ean
                FROM final_product FP
                WHERE FP.ean = ?
            ');

        $req->execute(array($ean));

        if ($req->rowCount() == 0) {
            throw new EANException('invalid EAN, unable to get masterEan');
        }
        
        $res = $req->fetch();
        $masterEan = $res['ean'];

        if ($res['family_id'] == 17) {
            $req = $this
                ->connection
                ->prepare('
                SELECT 
                  FP.finishing_id,
                  FP.mounting_id,
                  FP.mounting_color_id,
                  FP.ean,
                  FP.visual_id
                FROM final_product FP
                WHERE FP.ean = ?
            ');

            $req->execute(array($ean));
            $res = $req->fetch();

            if ($res['finishing_id'] == 5 || $res['finishing_id'] == 6) {
                if ($res['mounting_id'] == 5 && $res['mounting_color_id'] == 2) {
                    $masterEan = $res['ean'];
                } else {
                    $req = $this
                        ->connection
                        ->prepare('
                        SELECT FP.ean
                        FROM final_product FP
                        WHERE FP.visual_id = ?
                        AND FP.finishing_id = ?
                        AND FP.mounting_id = ?
                        AND FP.mounting_color_id = ?
                    ');
                    $params = array(
                        $res['visual_id'],
                        $res['finishing_id'],
                        5,
                        2
                    );

                    $req->execute($params);
                    $res = $req->fetch();
                    $masterEan = $res['ean'];
                }
            }
        } else {
            $masterEan = $res['ean'];
        }

        return $masterEan;
    }
}