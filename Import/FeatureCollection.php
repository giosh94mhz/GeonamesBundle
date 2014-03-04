<?php
namespace Giosh94mhz\GeonamesBundle\Import;

use Giosh94mhz\GeonamesBundle\Model\Feature;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;

class FeatureCollection extends ArrayCollection
{
    private $repo;
    private $include;
    private $exclude;
    private $forceInclude;

    public function __construct(ObjectRepository $repo, array $include, array $exclude = array(), array $forceInclude = array())
    {
        parent::__construct();

        $this->repo = $repo;
        $this->include = $include;
        $this->exclude = $exclude;
        $this->forceInclude = $forceInclude;

        $this->refresh();
    }

    public function refresh()
    {
        $this->clear();
        foreach ($this->repo->findAll() as $feature) {
            $key = $this->getKey($feature);
            if ($this->isKeyIncluded($key))
                $this->set($key, $feature);
        }
    }

    private function getKey(Feature $feature)
    {
        return $feature->getClass() . '.' . $feature->getCode();
    }

    private function isKeyIncluded($key)
    {
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
