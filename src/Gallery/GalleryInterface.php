<?php

namespace apiSfs\src\Gallery;

interface GalleryInterface
{
    public function isValidCegidId($cegidID);

    public function getGalleryList($type);

    public function getCloseGalleryList();
}