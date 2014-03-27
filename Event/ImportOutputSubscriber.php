<?php
namespace Giosh94mhz\GeonamesBundle\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Giosh94mhz\GeonamesBundle\GeonamesImportEvents;
use Symfony\Component\Stopwatch\Stopwatch;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder;

class ImportOutputSubscriber implements EventSubscriberInterface
{
    private $output;

    private $logger;

    private $stopWatch;

    private $progress;

    private $onProgress;

    public static function getSubscribedEvents()
    {
        return array(
            GeonamesImportEvents::PRE_IMPORT              => 'preImport',
            GeonamesImportEvents::PRE_DOWNLOAD            => 'preDownload',
            GeonamesImportEvents::ON_DOWNLOAD_PROGRESS    => 'onDownloadProgress',
            GeonamesImportEvents::POST_DOWNLOAD           => 'postDownload',
            GeonamesImportEvents::PRE_IMPORT_STEP         => 'preImportStep',
            GeonamesImportEvents::ON_IMPORT_STEP_PROGRESS => 'onImportProgress',
            GeonamesImportEvents::POST_IMPORT_STEP        => 'postImportStep',
            GeonamesImportEvents::POST_IMPORT             => 'postImport',
            GeonamesImportEvents::ON_ERROR                => 'onError',
            GeonamesImportEvents::ON_SKIP                 => 'onSkip'
        );
    }

    public function __construct(OutputInterface $output, ProgressHelper $progress)
    {
        $this->output = $output;
        $this->progress = $progress;
        $this->onProgress = false;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function getStopWatch()
    {
        return $this->stopWatch;
    }

    public function setStopWatch(Stopwatch $stopWatch)
    {
        $this->stopWatch = $stopWatch;

        return $this;
    }

    public function preImport(PreImportEvent $event)
    {
        $this->output->writeln("<info>Geonames import started</info>");
        if ($this->stopWatch)
            $this->stopWatch->start('geonamesImport');
    }

    public function postImport(PostImportEvent $event)
    {
        $this->finish();
        $stopWatchEvent = $this->stopWatch ? $this->stopWatch->stop('geonamesImport') : null;
        $this->logPostImportEvent($event, $stopWatchEvent);
    }

    public function preDownload(ImportEvent $event)
    {
        $this->finish();
        $this->output->writeln("<info>Downloading required dump files...</info>");
    }

    public function onDownloadProgress(OnProgressEvent $event)
    {
        $this->progress($event->getTotal(), $event->getCurrent());
    }

    public function postDownload(ImportEvent $event)
    {
        $this->finish();
        $this->output->writeln("<info>...download completed.</info>");
    }

    public function preImportStep(PreImportEvent $event)
    {
        $this->finish();
        if ($this->stopWatch)
            $this->stopWatch->start('geonamesImportStep');

        $name = $this->getBuilderName($event->getBuilder());

        $this->output->writeln("<info>Import {$name}</info>");
    }

    public function onImportProgress(OnProgressEvent $event)
    {
        $this->progress($event->getTotal(), $event->getCurrent());
    }

    public function postImportStep(PostImportEvent $event)
    {
        $this->finish();
        $stopWatchEvent = $this->stopWatch ? $this->stopWatch->stop('geonamesImportStep') : null;
        $this->logPostImportEvent($event, $stopWatchEvent);
    }

    public function onError(ImportErrorEvent $event)
    {
        if ($this->logger) {
            $this->logger->error($event->getException()->getMessage(),array(
                'class' => $event->getClass(),
                'value' => $event->getValue(),
                'exception' => $event->getException()->getTrace()
            ));
        }
    }

    public function onSkip(ImportErrorEvent $event)
    {
        if ($this->logger) {
            $this->logger->debug($event->getException()->getMessage(),array(
                'class' => $event->getClass(),
                'value' => $event->getValue(),
                'exception' => $event->getException()->getTrace()
            ));
        }
    }

    private function start($total)
    {
        $this->progress->setRedrawFrequency(min(max(1, $total / 1000), 10000));
        $this->progress->start($this->output, $total);
        $this->progress->setCurrent(0, true);
        $this->onProgress = true;
    }

    private function progress($total, $current)
    {
        if (! $this->onProgress)
            $this->start($total);
        $this->progress->setCurrent($current);
    }

    private function finish()
    {
        if ($this->onProgress) {
            $this->onProgress = false;
            $this->progress->finish();
        }
    }

    private function logPostImportEvent(PostImportEvent $event, $verbosity, $stopWatchEvent = null)
    {
        if ($stopWatchEvent) {
            $elapsed = $event->getDuration();
            $this->output->writeln(
                sprintf("<info>Imported %d of %d values (%d skipped, %d errors) in %.4fs</info>",
                    $event->getSynced(), $event->getTotal(), $event->getSkipped(), $event->getErrors(),
                    $elapsed / 1000.),
                OutputInterface::OUTPUT_NORMAL
            );
        } else {
            $this->output->writeln(
                sprintf("<info>Imported %d of %d values (%d skipped, %d errors)</info>",
                    $event->getSynced(), $event->getTotal(), $event->getSkipped(), $event->getErrors()),
                OutputInterface::OUTPUT_NORMAL
            );
        }
    }

    private function getBuilderName(ImportStepBuilder $builder)
    {
        return preg_replace(
            '/^Giosh94mhz\\\\GeonamesBundle\\\\Import\\\\StepBuilder\\\\(\\w+)ImportStepBuilder$/',
            '$1',
            get_class($builder)
        );
    }
}
