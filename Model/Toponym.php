<?php

namespace Giosh94mhz\GeonamesBundle\Model;

interface Toponym
{
    /**
     * Geonames id
     * @return integer
     */
    public function getId();

    /**
     * name of geographical point
     * @return string
     */
    public function getName();

    /**
     * name of geographical point in plain ascii characters
     * @return string
     */
    public function getAsciiName();

    /**
     * A collection of alternate names
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAlternateNames();

    /**
     * An array of alternate names (string only)
     * @return array
     */
    public function getAlternateNamesArray();

    /**
     * latitude in decimal degrees
     * @return float
     */
    public function getLatitude();

    /**
     * longitude in decimal degrees
     * @return float
     */
    public function getLongitude();

    /**
     * feture class
     * @see http://www.geonames.org/export/codes.html
     * @return Feature
     */
    public function getFeature();

    /**
     * ISO-3166 2-letter country code, 2 characters
     * @return string
     */
    public function getCountryCode();

    public function getAlternateCountryCodes();

    public function getAdmin1Code();

    public function getAdmin2Code();

    public function getAdmin3Code();

    public function getAdmin4Code();

    /**
     * population
     */
    public function getPopulation();

    /**
     * elevation in meters
     */
    public function getElevation();

    public function getGtopo30();

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents();

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren();

    /**
     * timezone
     * @return \DateTimeZone
     */
    public function getTimezone();
}
