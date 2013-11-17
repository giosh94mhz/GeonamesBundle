<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Geocoder\Result\ResultInterface;
use Geocoder\Result\AbstractResult;
use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;
use Giosh94mhz\GeonamesBundle\Exception\BadMethodCallException;

class ResultAdapter extends AbstractResult implements ResultInterface
{

    protected $toponym;

    protected $loader;

    public function __construct(Toponym $toponym = null)
    {
        $this->toponym = $toponym;
        $this->loader = function ($geonameid) {
            throw new BadMethodCallException(
                "A loaderClosure is required to call ResultAdapter::fromArray"
            );
        };
    }

    public function setToponym(Toponym $toponym)
    {
        $this->toponym = $toponym;
    }

    public function setLoaderClosure(\Closure $loader)
    {
        $this->loader = $loader;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCoordinates()
     */
    public function getCoordinates()
    {
        return array(
            $this->latitude,
            $this->longitude
        );
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getLatitude()
     */
    public function getLatitude()
    {
        return $this->toponym->getLatitude();
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getLongitude()
     */
    public function getLongitude()
    {
        return $this->toponym->getLongitude();
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getBounds() @todo
     */
    public function getBounds()
    {
        // TODO: Auto-generated method stub
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getStreetNumber() @todo
     */
    public function getStreetNumber()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getStreetName() @todo
     */
    public function getStreetName()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCity()
     */
    public function getCity()
    {
        return $this->name;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getZipcode() @todo
     */
    public function getZipcode()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCityDistrict() @todo
     */
    public function getCityDistrict()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCountry()
     */
    public function getCountry()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCountryCode()
     */
    public function getCountryCode()
    {
        return $this->toponym->getCountryCode();
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCounty()
     */
    public function getCounty()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getCountyCode()
     */
    public function getCountyCode()
    {
        // return $this->toponym->getAdmin2Code();
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getRegion()
     */
    public function getRegion()
    {
        return null;
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getRegionCode()
     */
    public function getRegionCode()
    {
        // return $this->toponym->getAdmin1Code();
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::getTimezone()
     */
    public function getTimezone()
    {
        return $this->toponym->getTimezone();
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::fromArray()
     */
    public function fromArray(array $data = array())
    {
        if (!isset($data['geonameid']))
            throw new InvalidArgumentException('Toponym cannot be loaded fromArray. '
                                               .'Parameters must contain the geonameid.');

        if ( isset($data['toponym']) && $data['toponym']->getId() == $data['geonameid'] ) {
            $this->toponym=$data['toponym'];
        } elseif ($this->loader) {
            $this->toponym=$this->loader($data['geonameid']);
        } else {
            throw new InvalidArgumentException('Toponym cannot be loaded fromArray. '
                                               .'The toponym is not preloaded, and there is no loader callback.');
        }
    }

    /*
     * (non-PHPdoc) @see \Geocoder\Result\ResultInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'geonameid' => $this->toponym->getId(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'bounds' => $this->getBounds(),
            'streetNumber' => $this->getStreetNumber(),
            'streetName' => $this->getStreetName(),
            'zipcode' => $this->getZipcode(),
            'city' => $this->getCity(),
            'cityDistrict' => $this->getCityDistrict(),
            'county' => $this->getCountry(),
            'countyCode' => $this->getCountryCode(),
            'region' => $this->getRegion(),
            'regionCode' => $this->getRegionCode(),
            'country' => $this->getCountry(),
            'countryCode' => $this->getCountryCode(),
            'timezone' => $this->getTimezone()
        );
    }
}
