<?php

namespace apiSfs\src\Gallery;

use apiSfs\core\Database\AbstractConnection;

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
                    cegid_id,
                    longitude,
                    latitude,
                    evoke_id,
                    cegid_id,
                    address_1,
                    name,
                    zipcode,
                    city,
                    email,
                    phone_main
                  FROM evoke_etablissement
                  WHERE shipfromstore = 1
                ';
                break;
            case 'clickAndCollectJ+O':
                $sql = '
                  SELECT 
                    cegid_id,
                    longitude,
                    latitude,
                    evoke_id,
                    cegid_id,
                    address_1,
                    name,
                    zipcode,
                    city,
                    email,
                    phone_main
                  FROM evoke_etablissement
                  WHERE clickandcollectj0 = 1
                ';
                break;
            default:
                $sql = '
                  SELECT 
                    cegid_id,
                    longitude,
                    latitude,
                    evoke_id,
                    cegid_id,
                    address_1,
                    name,
                    zipcode,
                    city,
                    email,
                    phone_main
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
        $res = $req->fetchAll();

        foreach ($res as $r) {
            $galleryList[$r['cegid_id']] = array(
                'lng' => $r['longitude'],
                'lat' => $r['latitude'],
                'evoke_id' => $r['evoke_id'],
                'cegid_id' => $r['cegid_id'],
                'address' => $r['address_1'],
                'name' => $r['name'],
                'zip' => $r['zipcode'],
                'city' => $r['city'],
                'email' => $r['email'],
                'phone' => $r['phone_main']
            );
        }
        array_walk_recursive($galleryList, function(&$item) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $galleryList;
    }

    public function getCloseGalleryList($latitude, $longitude)
    {
        $galleryList = $this->getGalleryList();

        

    }
}