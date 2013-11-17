<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\ToponymProxy;
use Giosh94mhz\GeonamesBundle\Model\Admin1 as Admin1Interface;

/**
 * Admin1
 */
class Admin1 extends ToponymProxy implements Admin1Interface
{
    /**
     * @var string
     */
    private $code;

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
     * @return Admin1
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
     * Set countryCode
     *
     * @param  string $countryCode
     * @return Admin1
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
     * @return Admin1
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
        return $this->name ?: $this->toponym->getAdmin();
    }

    /**
     * Set ASCII name
     *
     * @param  string $asciiName
     * @return Admin1
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
        return $this->asciiName ?: $this->toponym->getAsciiName();
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
