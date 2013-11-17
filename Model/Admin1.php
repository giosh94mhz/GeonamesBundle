<?php
namespace Giosh94mhz\GeonamesBundle\Model;

interface Admin1 extends Toponym
{
    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

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
