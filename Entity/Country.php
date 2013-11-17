<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\Country as CountryInterface;
use Giosh94mhz\GeonamesBundle\Model\ToponymProxy;

/**
 * Country
 */
class Country extends ToponymProxy implements CountryInterface
{
    /**
     * @var string
     */
    private $iso;

    /**
     * @var string
     */
    private $iso3;

    /**
     * @var integer
     */
    private $isoNumeric;

    /**
     * @var string
     */
    private $fipsCode;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $capital;

    /**
     * @var float
     */
    private $area;

    /**
     * @var integer
     */
    private $population;

    /**
     * @var string
     */
    private $continent;

    /**
     * @var string
     */
    private $topLevelDomain;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $currencyName;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $postalCodeFormat;

    /**
     * @var string
     */
    private $postalCodeRegex;

    /**
     * @var string
     */
    private $languages;

    /**
     * @var string
     */
    private $neighbours;

    /**
     * @var string
     */
    private $equivalentFipsCode;

    /**
     * @param string $iso ISO code of the country
     */
    public function __construct(Toponym $toponym)
    {
        $this->toponym = $toponym;
    }

    /**
     * Set iso
     *
     * @param  string  $iso
     * @return Country
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set iso3
     *
     * @param  string  $iso3
     * @return Country
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;

        return $this;
    }

    /**
     * Get iso3
     *
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Set isoNumeric
     *
     * @param  integer $isoNumeric
     * @return Country
     */
    public function setIsoNumeric($isoNumeric)
    {
        $this->isoNumeric = ($isoNumeric === null)? null : intval($isoNumeric);

        return $this;
    }

    /**
     * Get isoNumeric
     *
     * @return integer
     */
    public function getIsoNumeric()
    {
        return $this->isoNumeric;
    }

    /**
     * Set fipsCode
     *
     * @param  string  $fipsCode
     * @return Country
     */
    public function setFipsCode($fipsCode)
    {
        $this->fipsCode = $fipsCode;

        return $this;
    }

    /**
     * Get fipsCode
     *
     * @return string
     */
    public function getFipsCode()
    {
        return $this->fipsCode;
    }

    /**
     * Set name
     *
     * @param  string  $name
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set capital
     *
     * @param  string  $capital
     * @return Country
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;

        return $this;
    }

    /**
     * Get capital
     *
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Set area
     *
     * @param  float   $area
     * @return Country
     */
    public function setArea($area)
    {
        $this->area = ($area === null)? null : floatval($area);

        return $this;
    }

    /**
     * Get area
     *
     * @return float
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set population
     *
     * @param  integer $population
     * @return Country
     */
    public function setPopulation($population)
    {
        $this->population = intval($population);

        return $this;
    }

    /**
     * Get population
     *
     * @return integer
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set continent
     *
     * @param  string  $continent
     * @return Country
     */
    public function setContinent($continent)
    {
        $this->continent = $continent;

        return $this;
    }

    /**
     * Get continent
     *
     * @return string
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * Set topLevelDomain
     *
     * @param  string  $topLevelDomain
     * @return Country
     */
    public function setTopLevelDomain($topLevelDomain)
    {
        $this->topLevelDomain = $topLevelDomain;

        return $this;
    }

    /**
     * Get topLevelDomain
     *
     * @return string
     */
    public function getTopLevelDomain()
    {
        return $this->topLevelDomain;
    }

    /**
     * Set currency
     *
     * @param  string  $currency
     * @return Country
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currencyName
     *
     * @param  string  $currencyName
     * @return Country
     */
    public function setCurrencyName($currencyName)
    {
        $this->currencyName = $currencyName;

        return $this;
    }

    /**
     * Get currencyName
     *
     * @return string
     */
    public function getCurrencyName()
    {
        return $this->currencyName;
    }

    /**
     * Set phone
     *
     * @param  string  $phone
     * @return Country
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set postalCodeFormat
     *
     * @param  string  $postalCodeFormat
     * @return Country
     */
    public function setPostalCodeFormat($postalCodeFormat)
    {
        $this->postalCodeFormat = $postalCodeFormat;

        return $this;
    }

    /**
     * Get postalCodeFormat
     *
     * @return string
     */
    public function getPostalCodeFormat()
    {
        return $this->postalCodeFormat;
    }

    /**
     * Set postalCodeRegex
     *
     * @param  string  $postalCodeRegex
     * @return Country
     */
    public function setPostalCodeRegex($postalCodeRegex)
    {
        $this->postalCodeRegex = $postalCodeRegex;

        return $this;
    }

    /**
     * Get postalCodeRegex
     *
     * @return string
     */
    public function getPostalCodeRegex()
    {
        return $this->postalCodeRegex;
    }

    /**
     * Set languages
     *
     * @param  string  $languages
     * @return Country
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get languages
     *
     * @return string[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set neighbours
     *
     * @param  string  $neighbours
     * @return Country
     */
    public function setNeighbours($neighbours)
    {
        $this->neighbours = $neighbours;

        return $this;
    }

    /**
     * Get neighbours
     *
     * @return string
     */
    public function getNeighbours()
    {
        return $this->neighbours;
    }

    /**
     * Set equivalentFipsCode
     *
     * @param  string  $equivalentFipsCode
     * @return Country
     */
    public function setEquivalentFipsCode($equivalentFipsCode)
    {
        $this->equivalentFipsCode = $equivalentFipsCode;

        return $this;
    }

    /**
     * Get equivalentFipsCode
     *
     * @return string
     */
    public function getEquivalentFipsCode()
    {
        return $this->equivalentFipsCode;
    }

    /**
     * Get toponym
     *
     * @return \Giosh94mhz\GeonamesBundle\Entity\Toponym
     */
    public function getToponym()
    {
        return $this->toponym;
    }
}
