<?php
namespace Giosh94mhz\GeonamesBundle\Model;

interface Admin2 extends Toponym
{
    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Get admin1Code
     *
     * @return string
     */
    public function getAdmin1Code();

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get ASCII name
     *
     * @return string
     */
    public function getAsciiName();

    /**
     * Get toponym
     *
     * @return \Giosh94mhz\GeonamesBundle\Entity\Toponym
     */
    public function getToponym();
}
