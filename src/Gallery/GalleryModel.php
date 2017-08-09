<?php

namespace apiSfs\src\Gallery;

use apiSfs\core\Database\AbstractConnection;
use apiSfs\src\Utils\Utils;

class GalleryModel extends AbstractConnection implements GalleryInterface
{
    public function isValidCegidId($cegidID)
    {
        $req = $this
            ->connection
            ->prepare('
                SELECT EE.cegid_id
                FROM evoke_etablissement EE
                WHERE EE.cegid_id = ?
            ')
        ;
        $req->execute(array($cegidID));

        return $req->rowCount() > 0 ? true : false;
    }

    public function getGalleryList($type = null)
    {
        $galleryList = array();
        switch ($type) {
            case 'sfs':
                $sql = '
                  SELECT 
                    cegid_id AS cegidID,
                    name,
                    latitude,
                    longitude
                  FROM evoke_etablissement
                  WHERE shipfromstore = 1
                ';
                break;
            case 'clickAndCollectJ+O':
                $sql = '
                  SELECT 
                    cegid_id AS cegidID,
                    name,
                    latitude,
                    longitude
                  FROM evoke_etablissement
                  WHERE clickandcollectj0 = 1
                ';
                break;
            default:
                $sql = '
                  SELECT 
                    cegid_id AS cegidID,
                    name,
                    latitude,
                    longitude
                  FROM evoke_etablissement
                  WHERE shipfromstore = 1
                ';
                break;
        }

        $req = $this
            ->connection
            ->prepare($sql)
        ;
        $req->execute();

        return $req->fetchAll();
    }

    public function getCloseGalleryList($latitude, $longitude)
    {
        $galleryList = $this->getGalleryList();

        $resArray = array();
        foreach ($galleryList as $gallery) {
            $distance = Utils::getDistance($latitude, $longitude, $gallery['latitude'], $gallery['longitude']);
            if ($distance < MAX_PERIMETER) {
                $g = array();
                $g['distance'] = $distance;
                $g[$gallery['cegidID']] = $gallery;
                $resArray[$gallery['cegidID']] = $g;
            }
        }
        ksort($resArray);

        return $resArray;
    }
}