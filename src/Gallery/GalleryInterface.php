<?php

namespace apiSfs\src\Gallery;

/**
 * Interface GalleryInterface
 *
 * Describes Gallery related database interactions logic
 *
 * @package apiSfs\src\Gallery
 */
interface GalleryInterface
{
    /**
     * Checks if provided cegid ID exists
     * @param $cegidID
     * @return bool
     */
    public function isValidCegidId($cegidID);

    /**
     * Returns all galeries
     * @param $type
     * @return array
     */
    public function getGalleryList($type);

    /**
     * Return all galeries near to provided coordinates
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function getCloseGalleryList($latitude, $longitude);
}