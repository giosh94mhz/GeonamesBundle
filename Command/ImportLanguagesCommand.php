<?php
namespace Giosh94mhz\GeonamesBundle\Command;

use Giosh94mhz\GeonamesBundle\Model\Import\ImportDirector;
use Symfony\Component\Console\Input\InputInterface;

class ImportLanguagesCommand extends AbstractImportCommand
{
    public function __construct()
    {
        parent::__construct('geonames:import:languages', 'Import ISO639 languages');
    }

    protected function addSteps(ImportDirector $director, InputInterface $input)
    {
        $director->addStep($this->getContainer()->get('giosh94mhz_geonames.import.step.language'));
    }
}
