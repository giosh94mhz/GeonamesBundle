<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Geocoder\Result\ResultInterface;
use Geocoder\Result\AbstractResult;
use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;
use Giosh94mhz\GeonamesBundle\Exception\BadMethodCallException;
use Giosh94mhz\GeonamesBundle\Model\Feature;

class ResultAdapter extends AbstractResult implements ResultInterface
{

    protected $toponym;

    protected $loader;

    public function __construct(Toponym $toponym = null)
    {
        $this->toponym = $toponym;
        $this->loader = function ($geonameid) {
            throw new BadMethodCallException("A loaderClosure is required to call ResultAdapter::fromArray");
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

    public function getCoordinates()
    {
        return array(
            $this->getLatitude(),
            $this->getLongitude()
        );
    }

    public function getLatitude()
    {
        return $this->toponym->getLatitude();
    }

    public function getLongitude()
    {
        return $this->toponym->getLongitude();
    }

    public function getBounds()
    {
        return null;
    }

    public function getStreetNumber()
    {
        return null;
    }

    public function getStreetName()
    {
        return null;
    }

    public function getCity()
    {
        $feature = $this->toponym->getFeature();
        return ($feature->getClass() === 'P' && substr($feature->getCode(), 0, 2) !== 'PC' ) ? $this->toponym->getName() : null;
    }

    public function getZipcode()
    {
        return null;
    }

    public function getCityDistrict()
    {
        return null;
    }

    public function getCountry()
    {
        $feature = $this->toponym->getFeature();
        return ($feature->getClass() === 'P' && substr($feature->getCode(), 0, 2) === 'PC' ) ? $this->toponym->getName() : null;
    }

    public function getCountryCode()
    {
        return $this->toponym->getCountryCode();
    }

    public function getCounty()
    {
        return ($this->toponym->getFeature()->getClass() == 'ADM2') ? $this->toponym->getName() : null;
    }

    public function getCountyCode()
    {
        return $this->toponym->getAdmin2Code();
    }

    public function getRegion()
    {
        return ($this->toponym->getFeature()->getClass() == 'ADM1') ? $this->toponym->getName() : null;
    }

    public function getRegionCode()
    {
        return $this->toponym->getAdmin1Code();
    }

    public function getTimezone()
    {
        $tz = $this->toponym->getTimezone();
        return $tz ? $tz->getName() : null;
    }

    public function fromArray(array $data = array())
    {
        if (! isset($data['geonameid']))
            throw new InvalidArgumentException('Toponym cannot be loaded fromArray. ' . 'Parameters must contain the geonameid.');

        if (isset($data['toponym']) && $data['toponym']->getId() == $data['geonameid']) {
            $this->toponym = $data['toponym'];
        } elseif ($this->loader) {
            $this->toponym = $this->loader($data['geonameid']);
        } else {
            throw new InvalidArgumentException('Toponym cannot be loaded fromArray. ' . 'The toponym is not preloaded, and there is no loader callback.');
        }
    }

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
