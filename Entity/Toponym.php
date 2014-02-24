<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\Toponym as ToponymInterface;

/**
 * Toponym
 */
class Toponym implements ToponymInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $asciiName;

    /**
     * @var string[]
     */
    private $simpleAlternateNames;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $alternateCountryCodes;

    /**
     * @var string
     */
    private $admin1Code;

    /**
     * @var string
     */
    private $admin2Code;

    /**
     * @var string
     */
    private $admin3Code;

    /**
     * @var string
     */
    private $admin4Code;

    /**
     * @var integer
     */
    private $population;

    /**
     * @var integer
     */
    private $elevation;

    /**
     * @var integer
     */
    private $gtopo30;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var \DateTime
     */
    private $lastModify;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $alternateNames;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var Feature
     */
    private $feature;

    /**
     * Constructor
     */
    public function __construct($id)
    {
        $this->id = intval($id);
        $this->simpleAlternateNames = array();
        $this->alternateCountryCodes = array();

        $this->alternateNames = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param  string  $name
     * @return Toponym
     */
    public function setName($name)
    {
        $this->name = trim($name);

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
     * Set ASCII name
     *
     * @param  string  $asciiName
     * @return Toponym
     */
    public function setAsciiName($asciiName)
    {
        $this->asciiName = trim($asciiName);

        return $this;
    }

    /**
     * Get ASCII name
     *
     * @return string
     */
    public function getAsciiName()
    {
        return $this->asciiName;
    }

    /**
     * Set simpleAlternateNames
     *
     * @param  string[] $simpleAlternateNames
     * @return Toponym
     */
    public function setSimpleAlternateNames(array $simpleAlternateNames)
    {
        $this->simpleAlternateNames = $simpleAlternateNames;

        return $this;
    }

    /**
     * Get simpleAlternateNames
     *
     * @return string[]
     */
    public function getSimpleAlternateNames()
    {
        return $this->simpleAlternateNames;
    }

    /**
     * Set latitude
     *
     * @param  float   $latitude
     * @return Toponym
     */
    public function setLatitude($latitude)
    {
        $this->latitude = (float) $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param  float   $longitude
     * @return Toponym
     */
    public function setLongitude($longitude)
    {
        $this->longitude = (float) $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set countryCode
     *
     * @param  string  $countryCode
     * @return Toponym
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set alternateCountryCodes
     *
     * @param  string[] $alternateCountryCodes
     * @return Toponym
     */
    public function setAlternateCountryCodes(array $alternateCountryCodes)
    {
        $this->alternateCountryCodes = $alternateCountryCodes;

        return $this;
    }

    /**
     * Get alternateCountryCodes
     *
     * @return string[]
     */
    public function getAlternateCountryCodes()
    {
        return $this->alternateCountryCodes;
    }

    /**
     * Set admin1Code
     *
     * @param  string  $admin1Code
     * @return Toponym
     */
    public function setAdmin1Code($admin1Code)
    {
        $this->admin1Code = $admin1Code;

        return $this;
    }

    /**
     * Get admin1Code
     *
     * @return string
     */
    public function getAdmin1Code()
    {
        return $this->admin1Code;
    }

    /**
     * Set admin2Code
     *
     * @param  string  $admin2Code
     * @return Toponym
     */
    public function setAdmin2Code($admin2Code)
    {
        $this->admin2Code = $admin2Code;

        return $this;
    }

    /**
     * Get admin2Code
     *
     * @return string
     */
    public function getAdmin2Code()
    {
        return $this->admin2Code;
    }

    /**
     * Set admin3Code
     *
     * @param  string  $admin3Code
     * @return Toponym
     */
    public function setAdmin3Code($admin3Code)
    {
        $this->admin3Code = $admin3Code;

        return $this;
    }

    /**
     * Get admin3Code
     *
     * @return string
     */
    public function getAdmin3Code()
    {
        return $this->admin3Code;
    }

    /**
     * Set admin4Code
     *
     * @param  string  $admin4Code
     * @return Toponym
     */
    public function setAdmin4Code($admin4Code)
    {
        $this->admin4Code = $admin4Code;

        return $this;
    }

    /**
     * Get admin4Code
     *
     * @return string
     */
    public function getAdmin4Code()
    {
        return $this->admin4Code;
    }

    /**
     * Set population
     *
     * @param  integer $population
     * @return Toponym
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population
     *
     * @return integer
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set elevation
     *
     * @param  integer $elevation
     * @return Toponym
     */
    public function setElevation($elevation)
    {
        $this->elevation = $elevation;

        return $this;
    }

    /**
     * Get elevation
     *
     * @return integer
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * Set gtopo30
     *
     * @param  integer $gtopo30
     * @return Toponym
     */
    public function setGtopo30($gtopo30)
    {
        $this->gtopo30 = intval($gtopo30);

        return $this;
    }

    /**
     * Get gtopo30
     *
     * @return integer
     */
    public function getGtopo30()
    {
        return $this->gtopo30;
    }

    /**
     * Set timezone
     *
     * @param  \DateTimeZone $timezone
     * @return Toponym
     */
    public function setTimezone(\DateTimeZone $timezone)
    {
        $this->timezone = $timezone->getName();

        return $this;
    }

    /**
     * Get timezone
     *
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->timezone ? new \DateTimeZone($this->timezone) : null;
    }

    /**
     * Set lastModify
     *
     * @param  \DateTime $lastModify
     * @return Toponym
     */
    public function setLastModify(\DateTime $lastModify)
    {
        if ($lastModify != $this->lastModify)
            $this->lastModify = $lastModify;

        return $this;
    }

    /**
     * Get lastModify
     *
     * @return \DateTime
     */
    public function getLastModify()
    {
        return $this->lastModify;
    }

    /**
     * Set id
     *
     * @param  integer $id
     * @return Toponym
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
     * Add alternateNames
     *
     * @param  AlternateName $alternateNames
     * @return Toponym
     */
    public function addAlternateName(AlternateName $alternateNames)
    {
        $this->alternateNames[] = $alternateNames;

        return $this;
    }

    /**
     * Remove alternateNames
     *
     * @param AlternateName $alternateNames
     */
    public function removeAlternateName(AlternateName $alternateNames)
    {
        $this->alternateNames->removeElement($alternateNames);
    }

    /**
     * Get alternateNames
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlternateNames()
    {
        return $this->alternateNames;
    }

    /**
     * Add parents
     *
     * @param  HierarchyLink $parents
     * @return Toponym
     */
    public function addParent(HierarchyLink $parents)
    {
        $this->parents[] = $parents;

        return $this;
    }

    /**
     * Remove parents
     *
     * @param HierarchyLink $parents
     */
    public function removeParent(HierarchyLink $parents)
    {
        $this->parents->removeElement($parents);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add children
     *
     * @param  HierarchyLink $children
     * @return Toponym
     */
    public function addChild(HierarchyLink $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param HierarchyLink $children
     */
    public function removeChild(HierarchyLink $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set feature
     *
     * @param  Feature $feature
     * @return Toponym
     */
    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return Feature
     */
    public function getFeature()
    {
        return $this->feature;
    }
}
