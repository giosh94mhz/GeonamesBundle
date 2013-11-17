<?php

namespace Giosh94mhz\GeonamesBundle\Model;

/**
 * Continent
 */
interface Continent extends Toponym
{
    /**
     * Get toponym
     *
     * @return Toponym
     */
    public function getToponym();

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

}
