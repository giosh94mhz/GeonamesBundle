<?php

namespace Giosh94mhz\GeonamesBundle\Model;

/**
 * Country
 */
interface Country extends Toponym
{
    /**
     * Get toponym
     *
     * @return Toponym
     */
    public function getToponym();

    /**
     * Get iso2
     *
     * @return string
     */
    public function getIso();

    /**
     * Get iso3
     *
     * @return string
     */
    public function getIso3();

    /**
     * Get isoNumeric
     *
     * @return integer
     */
    public function getIsoNumeric();

    /**
     * Get fipsCode
     *
     * @return string
     */
    public function getFipsCode();

    /**
     * Get capital
     *
     * @return string
     */
    public function getCapital();

    /**
     * Get area
     *
     * @return float
     */
    public function getArea();

    /**
     * Get population
     *
     * @return integer
     */
    public function getPopulation();

    /**
     * Get continent
     *
     * @return string
     */
    public function getContinent();

    /**
     * Get tld
     *
     * @return string
     */
    public function getTopLevelDomain();

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency();

    /**
     * Get currencyName
     *
     * @return string
     */
    public function getCurrencyName();

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Get postalCodeFormat
     *
     * @return string
     */
    public function getPostalCodeFormat();

    /**
     * Get postalCodeRegex
     *
     * @return string
     */
    public function getPostalCodeRegex();

    /**
     * Get languages
     *
     * @return string
     */
    public function getLanguages();

    /**
     * Get neighbours
     *
     * @return string
     */
    public function getNeighbours();

    /**
     * Get equivalentFipsCode
     *
     * @return string
     */
    public function getEquivalentFipsCode();

}
