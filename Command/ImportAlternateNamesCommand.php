<?php
namespace Giosh94mhz\GeonamesBundle\Command;

use Giosh94mhz\GeonamesBundle\Model\Import\ImportDirector;
use Symfony\Component\Console\Input\InputInterface;

class ImportAlternateNamesCommand extends AbstractImportCommand
{
    protected function configure()
    {
        $this->setName('geonames:import:alternate-names')
             ->setDescription('Import alternate names for toponyms')
             ->configureCommon();
    }

    protected function addSteps(ImportDirector $director, InputInterface $input)
    {
        $director->addStep($this->getContainer()->get('giosh94mhz_geonames.import.step.alternate_name'));
    }
}
