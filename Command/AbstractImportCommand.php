<?php
namespace Giosh94mhz\GeonamesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Giosh94mhz\GeonamesBundle\Event\ImportOutputSubscriber;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportDirector;

abstract class AbstractImportCommand extends ContainerAwareCommand
{
    private $downloadDir;

    protected function configureCommon()
    {
        $this
        ->addOption(
            'fetch-only',
            null,
            InputOption::VALUE_NONE,
            'Download required files without importing'
        )
        ;

        return $this;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $director=$container->get('giosh94mhz_geonames.import.director');

        $this->addSteps($director, $input);

        $dispatcher = $container->get('event_dispatcher');
        if ($dispatcher) {
            $outputSubscriber = new ImportOutputSubscriber($output, $this->getHelperSet()->get('progress'));
            $logger = $container->get('giosh94mhz_geonames.logger');
            if ($logger)
                $outputSubscriber->setLogger($logger);

            $dispatcher->addSubscriber($outputSubscriber);
        }

        if ($input->getOption('fetch-only')) {
            $director->download();
        } else {
            $director->import();
        }
    }

    abstract protected function addSteps(ImportDirector $director, InputInterface $input);
}
