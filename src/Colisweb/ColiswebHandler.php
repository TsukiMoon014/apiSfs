<?php

namespace apiSfs\src\Colisweb;

use apiSfs\core\Database\Connection;
use apiSfs\core\Exceptions\ColiswebException;
use apiSfs\core\Exceptions\EANException;
use apiSfs\core\Exceptions\GalleryException;
use apiSfs\core\Exceptions\PackageException;
use apiSfs\src\EAN\EANHandler;
use apiSfs\src\Gallery\GalleryModel;
use apiSfs\src\Package\PackageModel;
use Curl\Curl;

class ColiswebHandler implements ColiswebInterface
{
    public function getCarrierTiming($cegidID, $postalCode, $eanString)
    {
        $galleryModel = new GalleryModel(Connection::getConnection());
        try {
            $storeCode = $galleryModel->getStoreCode($cegidID);
        } catch (GalleryException $exception) {
            throw new ColiswebException($exception->getMessage());
        }

        try {
            $eans = EANHandler::getEansFromString($eanString);
        } catch (EANException $exception) {
            throw new ColiswebException($exception->getMessage());
        }

        $packageModel = new PackageModel(Connection::getConnection());
        $packageArray = array();
        foreach ($eans as $ean) {
            try {
                array_push($packageArray, $packageModel->getPackageDescriptionByEan($ean));
            } catch (PackageException $exception) {
                throw new ColiswebException($exception->getMessage());
            }
        }

        $packages = array();

        foreach ($packageArray as $package) {
            $p = array(
                'description' => 'Package',
                'height' => $package['height'],
                'length' => $package['length'],
                'width' => $package['width'],
                'weight' => $package['weight']
            );
            array_push($packages, $p);
        }
        $postFields = array(
            'route' => array(
                array(
                    'type' => 'pickup',
                    'location' => array(
                        'type' => 'city',
                        'store_code' => $storeCode
                    )
                ),
                array(
                    'type' => 'shipping',
                    'location' => array(
                        'type' => 'city',
                        'postal_code' => $postalCode
                    )
                ),
            ),
            'packaging' => array(
                'global_description' => 'Packages',
                'packets' => $packages
            )
        );

        $postFields = json_encode($postFields);

        $curl = new Curl();
        if (true === PRODUCTION_ENVIRONMENT) {
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
            $curl->setHeader('api-key', COLISWEB['production']['api_key']);
            $curl->setHeader('content-type', 'application/json');
            $curl->post(COLISWEB['production']['base_url'].'/4/deliveries/options', $postFields);
        } else {
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
            $curl->setHeader('api-key', COLISWEB['development']['api_key']);
            $curl->setHeader('content-type', 'application/json');
            $curl->post(COLISWEB['development']['base_url'].'/4/deliveries/options', $postFields);
        }

        if ($curl->error) {
            throw new ColiswebException('Colisweb error: '.$curl->error_code.' - '.$curl->error_message);
        }
        else {
            return $curl->response;
        }
    }

}