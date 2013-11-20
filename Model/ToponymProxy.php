<?php
namespace Giosh94mhz\GeonamesBundle\Model;

use Giosh94mhz\GeonamesBundle\Model\Toponym;

class ToponymProxy implements Toponym
{
    /**
     * @var \Giosh94mhz\GeonamesBundle\Model\Toponym
     */
    protected $toponym;

    public function getId()
    {
        return $this->toponym->getId();
    }

    public function getName()
    {
        return $this->toponym->getName();
    }

    public function getAsciiName()
    {
        return $this->toponym->getAsciiName();
    }

    public function getAlternateNames()
    {
        return $this->toponym->getAlternateNames();
    }

    public function getLatitude()
    {
        return $this->toponym->getLatitude();
    }

    public function getLongitude()
    {
        return $this->toponym->getLongitude();
    }

    public function getFeature()
    {
        return $this->toponym->getFeature();
    }

    public function getCountryCode()
    {
        return $this->toponym->getCountryCode();
    }

    public function getAlternateCountryCodes()
    {
        return $this->toponym->getAlternateCountryCodes();
    }

    public function getAdmin1Code()
    {
        return $this->toponym->getAdmin1Code();
    }

    public function getAdmin2Code()
    {
        return $this->toponym->getAdmin2Code();
    }

    public function getAdmin3Code()
    {
        return $this->toponym->getAdmin3Code();
    }

    public function getAdmin4Code()
    {
        return $this->toponym->getAdmin4Code();
    }

    public function getPopulation()
    {
        return $this->toponym->getPopulation();
    }

    public function getElevation()
    {
        return $this->toponym->getElevation();
    }

    public function getGtopo30()
    {
        return $this->toponym->getGtopo30();
    }

    public function getTimezone()
    {
        return $this->toponym->getTimezone();
    }

}