<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\AlternateName as AlternateNameInterface;

/**
 * AlternateName
 */
class AlternateName implements AlternateNameInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Giosh94mhz\GeonamesBundle\Entity\Toponym
     */
    private $toponym;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $name;

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


    public function __construct($id)
    {
        $this->id = intval($id);
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

    /**
     * Set language
     *
     * @param  string        $language
     * @return AlternateName
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set name
     *
     * @param  string        $name
     * @return AlternateName
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
    public function isPreferredName()
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
    public function isShortName()
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
    public function isColloquial()
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
    public function isHistoric()
    {
        return $this->isHistoric;
    }
}
