<?php

namespace Giosh94mhz\GeonamesBundle\Model;

/**
 * Language
 */
interface Language
{
    /**
     * Get iso639p3
     *
     * @return string
     */
    public function getIso639p3();

    /**
     * Get iso639p2
     *
     * @return string
     */
    public function getIso639p2();

    /**
     * Get iso639p1
     *
     * @return string
     */
    public function getIso639p1();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();
}
