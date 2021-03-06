<?php

namespace apiSfs\src\Stock;

use apiSfs\core\Database\AbstractConnection;
use apiSfs\src\EAN\EANHandler;

class StockModel extends AbstractConnection implements StockInterface
{
    public function getStockInfosByEan($cegidID, $ean)
    {
        $req = $this
            ->connection
            ->prepare('
                SELECT 
                  PCD.GQ_DEPOT AS depot,
                  PCD.GQ_PHYSIQUE AS stock,
                  M.codebarre AS ean,
                  FPD.calc_rangeYK AS classeRotation,
                  FE.size_type AS tailleEtablissement,
                  FPF.label AS typeProduit
                FROM produit_cegid_dispo PCD
                INNER JOIN produit_cegid_metabdepot PCM
                ON PCD.GQ_DEPOT = PCM.MDE_DEPOT
                INNER JOIN produit_mapping_ean M
                ON PCD.GQ_ARTICLE = M.ga_article
                LEFT JOIN final_product FP
                ON M.codebarre = FP.ean
                INNER JOIN final_product_digital FPD
                ON FPD.product_id = FP.product_id
                LEFT JOIN final_product_family FPF
                ON FP.family_id = FPF.family_id 
                INNER JOIN final_etablissement FE
                ON FE.cegid_id = PCM.MDE_ETABLISSEMENT
                WHERE PCM.MDE_ETABLISSEMENT = ?
                AND (PCD.GQ_PHYSIQUE > 0 OR PCD.GQ_RESERVECLI > 0)
                AND M.codebarre = ?
            ')
        ;

        $params = array($cegidID, $ean);
        $req->execute($params);
        $firstRes = $req->fetch();

        if ($firstRes['stock'] == '0') {
            return $firstRes;
        } else if (false === $firstRes) {
            return false;
        } else {
            $req = $this
                ->connection
                ->prepare('
                  SELECT 
                    FP.ean AS ean,
                    FP.visual_id AS visualID,
                    FP.family_id AS familyID,
                    FP.mounting_id AS mountingID
                  FROM final_product FP
                  WHERE FP.ean = ?
                ')
            ;
            
            $req->execute(array($ean));
            $productInfos = $req->fetch();
            
            if ($productInfos['familyID'] == '17') {
                return $firstRes;
            } else {
                $req = $this
                    ->connection
                    ->prepare('
                      SELECT FP.ean AS ean
                      FROM final_product FP
                      WHERE FP.visual_id = ?
                      AND FP.family_id = ?
                    ')
                ;

                $req->execute(
                    array(
                        $productInfos['visualID'],
                        $productInfos['familyID']
                    )
                );
                $eanList = $req->fetchAll(\PDO::FETCH_COLUMN);

                $sql = '
                  SELECT
                    PCD.GQ_DEPOT AS depot,
                    SUM(PCD.GQ_PHYSIQUE) AS stock,
                    M.codebarre AS ean,
                    FPD.calc_rangeYK AS classeRotation,
                    FE.size_type AS tailleEtablissement,
                    FPF.label AS typeProduit
                  FROM produit_cegid_dispo PCD
                  INNER JOIN produit_cegid_metabdepot PCM
                  ON PCD.GQ_DEPOT = PCM.MDE_DEPOT
                  INNER JOIN produit_mapping_ean M
                  ON PCD.GQ_ARTICLE = M.ga_article
                  LEFT JOIN final_product FP
                  ON M.codebarre = FP.ean
                  INNER JOIN final_product_digital FPD
                  ON FPD.product_id = FP.product_id
                  LEFT JOIN final_product_family FPF
                  ON FP.family_id = FPF.family_id 
                  INNER JOIN final_etablissement FE
                  ON FE.cegid_id = PCM.MDE_ETABLISSEMENT
                  WHERE PCM.MDE_ETABLISSEMENT = ?
                  AND (PCD.GQ_PHYSIQUE > 0 OR PCD.GQ_RESERVECLI > 0)
                  AND M.codebarre IN('
                ;

                foreach ($eanList as $key => $value) {
                    if ($key == count($eanList) - 1) {
                        $sql .= '"' . $value . '")';
                    } else {
                        $sql .= '"' . $value . '",';
                    }
                }

                $req = $this
                    ->connection
                    ->prepare($sql)
                ;
                $req->execute(array($cegidID));
                $res = $req->fetch();
            }
            
            return $res;
        }
    }

    public function isStockAvailable($cegidID, $eanList)
    {
        $eanInfos = EANHandler::getEansInfosFromEanString($eanList);

        $stockAvailable = true;
        $fullStockInfos = array();
        foreach ($eanInfos as $ean => $qtyNeeded) {
            $stockInfos = $this->getStockInfosByEan($cegidID, strval($ean));
            if (false === $stockInfos) {
                $stockAvailable = false;
            } else {
                switch ($stockInfos['typeProduit']) {
                    case 'CLASSIC':
                        switch ($stockInfos['classeRotation']) {
                            case 'A':
                                switch ($stockInfos['tailleEtablissement']) {
                                    case '1':
                                        if ($stockInfos['stock'] - 4 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    case '2':
                                        if ($stockInfos['stock'] - 3 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    case '3':
                                    case '4':
                                        if ($stockInfos['stock'] - 2 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    default:
                                        if ($stockInfos['stock'] < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                }
                                break;
                            case 'B':
                                switch ($stockInfos['tailleEtablissement']) {
                                    case '1':
                                        if ($stockInfos['stock'] - 3 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    case '2':
                                    case '3':
                                    case '4':
                                        if ($stockInfos['stock'] - 2 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    default:
                                        if ($stockInfos['stock'] < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                }
                                break;
                            default:
                                if ($stockInfos['stock'] < $qtyNeeded) {
                                    $stockAvailable = false;
                                }
                                break;
                        }
                        break;
                    case 'LARGE FORMAT':
                        switch ($stockInfos['classeRotation']) {
                            case 'A':
                                switch ($stockInfos['tailleEtablissement']) {
                                    case '1':
                                        if ($stockInfos['stock'] - 2 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    case '2':
                                    case '3':
                                    case '4':
                                        if ($stockInfos['stock'] - 1 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    default:
                                        if ($stockInfos['stock'] < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                }
                                break;
                            case 'B':
                                if ($stockInfos['stock'] - 1 < $qtyNeeded) {
                                    $stockAvailable = false;
                                }
                                break;
                            default:
                                if ($stockInfos['stock'] < $qtyNeeded) {
                                    $stockAvailable = false;
                                }
                                break;
                        }
                        break;
                    case 'GIANT FORMAT':
                        switch ($stockInfos['classeRotation']) {
                            case 'A':
                                switch ($stockInfos['tailleEtablissement']) {
                                    case '1':
                                        if ($stockInfos['stock'] - 2 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    case '2':
                                    case '3':
                                    case '4':
                                        if ($stockInfos['stock'] - 1 < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                    default:
                                        if ($stockInfos['stock'] < $qtyNeeded) {
                                            $stockAvailable = false;
                                        }
                                        break;
                                }
                                break;
                            default:
                                if ($stockInfos['stock'] < $qtyNeeded) {
                                    $stockAvailable = false;
                                }
                                break;
                        }
                        break;
                    default:
                        if ($stockInfos['stock'] < $qtyNeeded) {
                            $stockAvailable = false;
                        }
                        break;
                }
            }
            if (false === $stockAvailable) {
                break;
            } else {
                array_push($fullStockInfos, $stockInfos);
            }
        }
        if (true === $stockAvailable) {
           return $fullStockInfos;
        } else {
            return false;
        }
    }
}