<?php
namespace Giosh94mhz\GeonamesBundle\Import;

class FeatureMatch
{
    private $include;

    private $exclude;

    private $forceInclude;

    public function __construct(array $include, array $exclude = array(), array $forceInclude = array())
    {
        $this->include = $include;
        $this->exclude = $exclude;
        $this->forceInclude = $forceInclude;
    }

    public function getInclude()
    {
        return $this->include;
    }

    public function setInclude($include)
    {
        $this->include = $include;

        return $this;
    }

    public function getExclude()
    {
        return $this->exclude;
    }

    public function setExclude($exclude)
    {
        $this->exclude = $exclude;

        return $this;
    }

    public function getForceInclude()
    {
        return $this->forceInclude;
    }

    public function setForceInclude($forceInclude)
    {
        $this->forceInclude = $forceInclude;

        return $this;
    }

    public function isIncluded($class, $code)
    {
        $key = $class . '.' . $code;

        foreach ($this->forceInclude as $pattern)
            if (fnmatch($pattern, $key))
                return true;

        foreach ($this->exclude as $pattern)
            if (fnmatch($pattern, $key))
                return false;

        foreach ($this->include as $pattern)
            if (fnmatch($pattern, $key))
                return true;

        return false;
    }
}
