<?php

namespace Giosh94mhz\GeonamesBundle\Model;

interface Toponym
{
    /**
     * integer id of record in geonames database
     */
    public function getId();

    /**
     * name of geographical point
     */
    public function getName();

    /**
     * name of geographical point in plain ascii characters
     */
    public function getAsciiName();

    /**
     * an array of alternate names
     */
    public function getAlternateNames();

    /**
     * latitude in decimal degrees
     */
    public function getLatitude();

    /**
     * longitude in decimal degrees
     */
    public function getLongitude();

    /**
     * feture class
     * @see http://www.geonames.org/export/codes.html
     */
    public function getFeature();

    /**
     * ISO-3166 2-letter country code, 2 characters
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
     * timezone
     * @return \DateTimeZone
     */
    public function getTimezone();
}
