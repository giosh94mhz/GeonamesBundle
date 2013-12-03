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
     * Get isoLanguage
     *
     * @return string
     */
    public function getIsoLanguage();

    /**
     * Get alternateName
     *
     * @return string
    */
    public function getAlternateName();

    /**
     * Get isPreferredName
     *
     * @return boolean
    */
    public function getIsPreferredName();

    /**
     * Get isShortName
     *
     * @return boolean
    */
    public function getIsShortName();

    /**
     * Get isColloquial
     *
     * @return boolean
    */
    public function getIsColloquial();

    /**
     * Get isHistoric
     *
     * @return boolean
    */
    public function getIsHistoric();

    /**
     * Get toponym
     *
     * @return Toponym
    */
    public function getToponym();
}
