<?php
namespace Giosh94mhz\GeonamesBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder;

class PreImportEvent extends Event
{
    protected $builder;

    public function getBuilder()
    {
        return $this->builder;
    }

    public function setBuilder(ImportStepBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }
}
