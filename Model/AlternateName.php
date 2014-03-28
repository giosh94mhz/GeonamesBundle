<?php

namespace Giosh94mhz\GeonamesBundle\Model;

/**
 * AlternateName
 */
interface AlternateName
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Get language or type
     *
     * @todo Find a way to keep only ISO 639p3 string (or Language entity)
     * @return string ISO 639p1 if a two-char string;
     *                ISO 639p3 if a three-char string;
     *                "post" for postal code
     *                "iata"/"icao"/"faac" for airport codes;
     *                "fr_1793" for French Revolution names;
     *                "abbr" for abbreviation;
     *                "link" for website.
     */
    public function getLanguage();

    /**
     * Get name
     *
     * @return string
    */
    public function getName();

    /**
     * Get isPreferredName
     *
     * @return boolean
     */
    public function isPreferredName();

    /**
     * Get isShortName
     *
     * @return boolean
    */
    public function isShortName();

    /**
     * Get isColloquial
     *
     * @return boolean
     */
    public function isColloquial();

    /**
     * Get isHistoric
     *
     * @return boolean
     */
    public function isHistoric();

    /**
     * Get toponym
     *
     * @return Toponym
     */
    public function getToponym();
}
