<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\AlternateName as AlternateNameInterface;

/**
 * AlternateName
 */
class AlternateName implements AlternateNameInterface
{
    /**
     * @var string
     */
    private $isoLanguage;

    /**
     * @var string
     */
    private $alternateName;

    /**
     * @var boolean
     */
    private $isPreferredName;

    /**
     * @var boolean
     */
    private $isShortName;

    /**
     * @var boolean
     */
    private $isColloquial;

    /**
     * @var boolean
     */
    private $isHistoric;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Giosh94mhz\GeonamesBundle\Entity\Toponym
     */
    private $toponym;

    /**
     * Set isoLanguage
     *
     * @param  string        $isoLanguage
     * @return AlternateName
     */
    public function setIsoLanguage($isoLanguage)
    {
        $this->isoLanguage = $isoLanguage;

        return $this;
    }

    /**
     * Get isoLanguage
     *
     * @return string
     */
    public function getIsoLanguage()
    {
        return $this->isoLanguage;
    }

    /**
     * Set alternateName
     *
     * @param  string        $alternateName
     * @return AlternateName
     */
    public function setAlternateName($alternateName)
    {
        $this->alternateName = $alternateName;

        return $this;
    }

    /**
     * Get alternateName
     *
     * @return string
     */
    public function getAlternateName()
    {
        return $this->alternateName;
    }

    /**
     * Set isPreferredName
     *
     * @param  boolean       $isPreferredName
     * @return AlternateName
     */
    public function setIsPreferredName($isPreferredName)
    {
        $this->isPreferredName = $isPreferredName;

        return $this;
    }

    /**
     * Get isPreferredName
     *
     * @return boolean
     */
    public function getIsPreferredName()
    {
        return $this->isPreferredName;
    }

    /**
     * Set isShortName
     *
     * @param  boolean       $isShortName
     * @return AlternateName
     */
    public function setIsShortName($isShortName)
    {
        $this->isShortName = $isShortName;

        return $this;
    }

    /**
     * Get isShortName
     *
     * @return boolean
     */
    public function getIsShortName()
    {
        return $this->isShortName;
    }

    /**
     * Set isColloquial
     *
     * @param  boolean       $isColloquial
     * @return AlternateName
     */
    public function setIsColloquial($isColloquial)
    {
        $this->isColloquial = $isColloquial;

        return $this;
    }

    /**
     * Get isColloquial
     *
     * @return boolean
     */
    public function getIsColloquial()
    {
        return $this->isColloquial;
    }

    /**
     * Set isHistoric
     *
     * @param  boolean       $isHistoric
     * @return AlternateName
     */
    public function setIsHistoric($isHistoric)
    {
        $this->isHistoric = $isHistoric;

        return $this;
    }

    /**
     * Get isHistoric
     *
     * @return boolean
     */
    public function getIsHistoric()
    {
        return $this->isHistoric;
    }

    /**
     * Set id
     *
     * @param  integer       $id
     * @return AlternateName
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set toponym
     *
     * @param  \Giosh94mhz\GeonamesBundle\Entity\Toponym $toponym
     * @return AlternateName
     */
    public function setToponym(\Giosh94mhz\GeonamesBundle\Entity\Toponym $toponym)
    {
        $this->toponym = $toponym;

        return $this;
    }

    /**
     * Get toponym
     *
     * @return \Giosh94mhz\GeonamesBundle\Entity\Toponym
     */
    public function getToponym()
    {
        return $this->toponym;
    }
}
