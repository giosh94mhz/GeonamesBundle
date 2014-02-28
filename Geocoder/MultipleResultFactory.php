<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Doctrine\Common\Persistence\ObjectManager;
use Geocoder\Result\MultipleResultFactory as GeocoderMultipleResultFactory;

class MultipleResultFactory extends GeocoderMultipleResultFactory
{
    protected $loader;

    public function __construct(ObjectManager $om)
    {
        $this->loader = new ResultLoader($om);
    }

    public function newInstance()
    {
        return $this->loader->newInstance();
    }
}
