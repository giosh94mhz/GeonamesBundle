<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\IsoLanguageCode as IsoLanguageCodeInterface;

/**
 * IsoLanguageCode
 */
class IsoLanguageCode implements IsoLanguageCodeInterface
{
    /**
     * @var string
     */
    private $iso639p2;

    /**
     * @var string
     */
    private $iso639p1;

    /**
     * @var string
     */
    private $languageName;

    /**
     * @var string
     */
    private $iso639p3;

    /**
     * Set iso639p2
     *
     * @param  string          $iso639p2
     * @return IsoLanguageCode
     */
    public function setIso639p2($iso639p2)
    {
        $this->iso639p2 = $iso639p2;

        return $this;
    }

    /**
     * Get iso639p2
     *
     * @return string
     */
    public function getIso639p2()
    {
        return $this->iso639p2;
    }

    /**
     * Set iso639p1
     *
     * @param  string          $iso639p1
     * @return IsoLanguageCode
     */
    public function setIso639p1($iso639p1)
    {
        $this->iso639p1 = $iso639p1;

        return $this;
    }

    /**
     * Get iso639p1
     *
     * @return string
     */
    public function getIso639p1()
    {
        return $this->iso639p1;
    }

    /**
     * Set languageName
     *
     * @param  string          $languageName
     * @return IsoLanguageCode
     */
    public function setLanguageName($languageName)
    {
        $this->languageName = $languageName;

        return $this;
    }

    /**
     * Get languageName
     *
     * @return string
     */
    public function getLanguageName()
    {
        return $this->languageName;
    }

    /**
     * Set iso639p3
     *
     * @param  string          $iso639p3
     * @return IsoLanguageCode
     */
    public function setIso639p3($iso639p3)
    {
        $this->iso639p3 = $iso639p3;

        return $this;
    }

    /**
     * Get iso639p3
     *
     * @return string
     */
    public function getIso639p3()
    {
        return $this->iso639p3;
    }
}
