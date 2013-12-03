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
     * Get toponym
     *
     * @return Toponym
     */
    public function getToponym();
}
