<?php

namespace Giosh94mhz\GeonamesBundle\Model;

/**
 * IsoLanguageCode
 */
interface IsoLanguageCode
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
     * Get languageName
     *
     * @return string
     */
    public function getLanguageName();
}
