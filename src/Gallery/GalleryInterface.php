<?php

namespace apiSfs\src\gallery;

interface GalleryInterface
{
    public function isValidCegidId($cegidID);

    public function getGalleryList($type);

    public function getCloseGalleryList();
}