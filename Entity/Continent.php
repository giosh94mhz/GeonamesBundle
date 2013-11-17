<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\Continent as ContinentInterface;
use Giosh94mhz\GeonamesBundle\Model\ToponymProxy;

/**
 * Continent
 */
class Continent extends ToponymProxy implements ContinentInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $code
     */
    public function __construct(Toponym $toponym)
    {
        $this->toponym = $toponym;
    }

    /**
     * Get code
     *
     * @param  string    $code
     * @return Continent
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
     * Set name
     *
     * @param  string    $name
     * @return Continent
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
        return $this->name ?: parent::getName();
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
