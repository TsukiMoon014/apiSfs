<?php

namespace apiSfs\src\gallery;

use apiSfs\core\database\AbstractConnection;

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
}