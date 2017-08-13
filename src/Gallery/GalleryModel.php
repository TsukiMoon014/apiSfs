<?php

namespace apiSfs\src\Gallery;

use apiSfs\core\Database\AbstractConnection;
use apiSfs\core\Exceptions\GalleryException;
use apiSfs\src\Utils\Utils;

/**
 * Class GalleryModel
 *
 * Provides Gallery module database interactions logic
 *
 * @package apiSfs\src\Gallery
 */
class GalleryModel extends AbstractConnection implements GalleryInterface
{
    /**
     * Checks if provided cegid ID exists
     * @param $cegidID
     * @return bool
     */
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

    /**
     * Returns all galeries
     * @param $type
     * @return array
     */
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

        if ($req->rowCount() == 0) {
            throw new GalleryException('No results for getGalleryList() method');
        } else {
            return $req->fetchAll();
        }
    }

    /**
     * Returns all galeries near to provided coordinates
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function getCloseGalleryList($latitude, $longitude)
    {
        $galleryList = $this->getGalleryList();

        $resArray = array();
        foreach ($galleryList as $gallery) {
            $distance = Utils::getDistance($latitude, $longitude, $gallery['latitude'], $gallery['longitude']);
            if ($distance < PERIMETER_MAX_PERIMETER) {
                $g = array();
                $g['distance'] = $distance;
                $g[$gallery['cegidID']] = $gallery;
                $resArray[$gallery['cegidID']] = $g;
            }
        }
        ksort($resArray);

        if (false !== empty($resArray)) {
            throw new GalleryException('No results for getCloseGalleryList() method');
        } else {
            return array_keys($resArray);
        }
    }

    /*
     * Returns provided cegidID relative store code
     * @param $cegidID
     * @return string
     */
    public function getStoreCode($cegidID)
    {
        $req = $this
            ->connection
            ->prepare('
              SELECT etablissement_id as storecode
              FROM final_etablissement
              WHERE cegid_id = ?
            '
        );
        $params = array($cegidID);
        $req->execute($params);

        if ($req->rowCount() == 0) {
            throw new GalleryException('Store code not found');
        } else {
            return $req->fetch(\PDO::FETCH_COLUMN);
        }
    }
}