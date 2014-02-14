<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\Language as LanguageInterface;

/**
 * ISO 639 Language
 */
class Language implements LanguageInterface
{
    /**
     * @var string
     */
    private $iso639p3;

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
    private $name;

    /**
     * Construct
     *
     * @param  string          $iso639p3
     * @return IsoLanguageCode
     */
    public function __construct($iso639p3)
    {
        $this->iso639p3 = $iso639p3;
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
     * Set name
     *
     * @param  string          $name
     * @return IsoLanguageCode
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
