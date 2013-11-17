<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\ToponymProxy;
use Giosh94mhz\GeonamesBundle\Model\Admin2 as Admin2Interface;

/**
 * Admin2
 */
class Admin2 extends ToponymProxy implements Admin2Interface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $admin1Code;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $asciiName;

    public function __construct(Toponym $toponym)
    {
        $this->toponym = $toponym;
    }

    /**
     * Set code
     *
     * @param  string $code
     * @return Admin2
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set admin1Code
     *
     * @param  string $admin1Code
     * @return Admin2
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
     * Set countryCode
     *
     * @param  string $countryCode
     * @return Admin2
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
     * Set name
     *
     * @param  string $name
     * @return Admin2
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
     * Set ASCII name
     *
     * @param  string $asciiName
     * @return Admin2
     */
    public function setAsciiName($asciiName)
    {
        $this->asciiName = $asciiName;

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
     * Set toponym
     *
     * @param  \Giosh94mhz\GeonamesBundle\Entity\Toponym $toponym
     * @return Admin2
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
