<?php
namespace Giosh94mhz\GeonamesBundle\Command;

use Giosh94mhz\GeonamesBundle\Model\Import\ImportDirector;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class ImportHierarchyCommand extends AbstractImportCommand
{
    public function __construct()
    {
        parent::__construct('geonames:import:hierarchy', 'Import toponym hierarchy tree');
    }

    protected function addSteps(ImportDirector $director, InputInterface $input)
    {
        $director->addStep($this->getContainer()->get('giosh94mhz_geonames.import.step.hierarchy'));
    }
}
