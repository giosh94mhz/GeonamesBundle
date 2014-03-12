<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
abstract class AbstractImportStepBuilder implements ImportStepBuilder
{
    const GEONAME_DUMP_URL = 'http://download.geonames.org/export/dump/';

    protected $dispatcher;

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }
}
