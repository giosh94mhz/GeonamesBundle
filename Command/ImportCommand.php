<?php
namespace Giosh94mhz\GeonamesBundle\Command;

use Giosh94mhz\GeonamesBundle\Command\AbstractImportCommand;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportDirector;
use Symfony\Component\Console\Input\InputInterface;

class ImportCommand extends AbstractImportCommand
{
    protected function configure()
    {
        $this->setName('geonames:import')
             ->setDescription('Import everything')
             ->configureCommon();
    }

    protected function addSteps(ImportDirector $director, InputInterface $input)
    {
        foreach ($this->getContainer()->get('giosh94mhz_geonames.import.all_steps') as $step)
            $director->addStep($step);
    }
}
