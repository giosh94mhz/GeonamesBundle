<?php
namespace Giosh94mhz\GeonamesBundle\Model\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface ToponymRepository extends ObjectRepository
{
    public function findByDistance($latitude, $longitude, $distanceInKm, $limit = null, $offset = null);

    public function findPlacesByDistance($latitude, $longitude, $distanceInKm, $country = null, $limit = null, $offset = null);
}
